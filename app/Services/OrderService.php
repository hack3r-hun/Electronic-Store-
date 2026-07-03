<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class OrderService
{
    public function updateStatus(Order $order, OrderStatus $status): Order
    {
        $order->update(['status' => $status]);

        if ($status === OrderStatus::Delivered && $order->payment_method->value === 'cod') {
            $order->update(['payment_status' => PaymentStatus::Paid]);
        }

        return $order->fresh();
    }

    public function generateInvoicePdf(Order $order): Response
    {
        $order->load(['items', 'user']);

        $pdf = Pdf::loadView('invoices.order', compact('order'));

        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    public function streamInvoicePdf(Order $order): Response
    {
        $order->load(['items', 'user']);

        $pdf = Pdf::loadView('invoices.order', compact('order'));

        return $pdf->stream("invoice-{$order->order_number}.pdf");
    }
}
