<?php

namespace App\Http\Controllers\Storefront;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected PaymentService $paymentService
    ) {}

    public function index()
    {
        $items = $this->cartService->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totals = $this->checkoutService->calculateTotals();

        return view('storefront.checkout.index', compact('items', 'totals'));
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $paymentMethod = PaymentMethod::from($request->payment_method);
            $order = $this->checkoutService->placeOrder($request->validated(), $paymentMethod);

            if ($paymentMethod === PaymentMethod::Stripe) {
                if ($this->paymentService->isConfigured()) {
                    return redirect()->route('checkout.pay', $order);
                }

                return redirect()->route('checkout.success', $order)
                    ->with('info', 'Order saved. Configure Stripe keys in .env to enable card payments.');
            }

            $this->sendOrderEmail($order);

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Order placed successfully! Pay cash on delivery.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function pay(Order $order): View|RedirectResponse
    {
        $this->authorizeOrder($order);

        if ($order->payment_method !== PaymentMethod::Stripe) {
            return redirect()->route('checkout.success', $order);
        }

        if (! $this->paymentService->isConfigured()) {
            return redirect()->route('checkout.success', $order)
                ->with('info', 'Stripe is not configured. Order saved as pending.');
        }

        try {
            $payment = $this->paymentService->createPaymentIntent($order);
        } catch (\Throwable $e) {
            return redirect()->route('checkout.success', $order)
                ->with('error', 'Could not initialize payment: '.$e->getMessage());
        }

        return view('storefront.checkout.pay', [
            'order' => $order,
            'stripeKey' => config('services.stripe.key'),
            'clientSecret' => $payment['client_secret'],
        ]);
    }

    public function confirmPayment(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);
        $this->paymentService->markPaid($order);
        $this->sendOrderEmail($order);

        return redirect()->route('checkout.success', $order)
            ->with('success', 'Payment successful! Thank you for your order.');
    }

    public function success(Order $order)
    {
        $this->authorizeOrder($order, allowGuest: true);

        return view('storefront.checkout.success', compact('order'));
    }

    protected function authorizeOrder(Order $order, bool $allowGuest = false): void
    {
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        if (! $allowGuest && ! $order->user_id && ! auth()->check()) {
            // guest orders allowed on success page only
        }
    }

    protected function sendOrderEmail(Order $order): void
    {
        $email = $order->user?->email ?? ($order->shipping_address['email'] ?? null);

        if ($email) {
            Mail::to($email)->send(new OrderPlaced($order));
        }
    }
}
