<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function items(): Collection
    {
        $query = CartItem::with('product.images');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', session()->getId());
        }

        return $query->get();
    }

    public function count(): int
    {
        return $this->items()->sum('quantity');
    }

    public function subtotal(): float
    {
        return $this->items()->sum(fn (CartItem $item) => $item->line_total);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        if ($quantity < 1) {
            return;
        }

        $quantity = min($quantity, $product->stock_quantity);

        if ($quantity < 1) {
            return;
        }

        $attributes = Auth::check()
            ? ['user_id' => Auth::id(), 'product_id' => $product->id]
            : ['session_id' => session()->getId(), 'product_id' => $product->id];

        $cartItem = CartItem::firstOrNew($attributes);
        $cartItem->quantity = min(
            ($cartItem->exists ? $cartItem->quantity : 0) + $quantity,
            $product->stock_quantity
        );

        try {
            $cartItem->save();
        } catch (UniqueConstraintViolationException) {
            // A concurrent request created the row first — merge into it instead.
            $existing = CartItem::where($attributes)->first();

            if ($existing) {
                $existing->update([
                    'quantity' => min($existing->quantity + $quantity, $product->stock_quantity),
                ]);
            }
        }
    }

    public function update(CartItem $cartItem, int $quantity): void
    {
        $this->authorizeItem($cartItem);

        if ($quantity < 1) {
            $cartItem->delete();

            return;
        }

        $cartItem->update([
            'quantity' => min($quantity, $cartItem->product->stock_quantity),
        ]);
    }

    public function remove(CartItem $cartItem): void
    {
        $this->authorizeItem($cartItem);
        $cartItem->delete();
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }
    }

    public function mergeGuestCart(int $userId): void
    {
        $sessionId = session()->getId();

        CartItem::where('session_id', $sessionId)->each(function (CartItem $guestItem) use ($userId) {
            $existing = CartItem::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existing) {
                $existing->update([
                    'quantity' => min(
                        $existing->quantity + $guestItem->quantity,
                        $guestItem->product->stock_quantity
                    ),
                ]);
                $guestItem->delete();
            } else {
                $guestItem->update([
                    'user_id' => $userId,
                    'session_id' => null,
                ]);
            }
        });
    }

    protected function authorizeItem(CartItem $cartItem): void
    {
        if (Auth::check() && $cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        if (! Auth::check() && $cartItem->session_id !== session()->getId()) {
            abort(403);
        }
    }
}
