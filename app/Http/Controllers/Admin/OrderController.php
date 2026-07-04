<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'user', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', array_column(OrderStatus::cases(), 'value'))],
        ]);

        $this->orderService->updateStatus($order, OrderStatus::from($request->status));

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $orderNumber = $order->order_number;
        $this->orderService->deleteOrder($order);

        return redirect()->route('admin.orders.index')
            ->with('success', "Order {$orderNumber} deleted and stock returned.");
    }

    public function invoice(Order $order): Response
    {
        return $this->orderService->streamInvoicePdf($order);
    }
}
