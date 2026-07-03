<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentService
{
    public function __construct()
    {
        if ($secret = config('services.stripe.secret')) {
            Stripe::setApiKey($secret);
        }
    }

    public function isConfigured(): bool
    {
        return ! empty(config('services.stripe.secret')) && ! empty(config('services.stripe.key'));
    }

    /**
     * @return array{client_secret: string, payment_intent_id: string}
     *
     * @throws ApiErrorException
     */
    public function createPaymentIntent(Order $order): array
    {
        $intent = PaymentIntent::create([
            'amount' => (int) round($order->total * 100),
            'currency' => strtolower(config('shop.currency', 'pkr')),
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ],
            'automatic_payment_methods' => ['enabled' => true],
        ]);

        $order->payment?->update([
            'stripe_payment_intent_id' => $intent->id,
            'meta' => ['client_secret' => $intent->client_secret],
        ]);

        return [
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
        ];
    }

    public function markPaid(Order $order, ?string $paymentIntentId = null): void
    {
        $order->update([
            'payment_status' => PaymentStatus::Paid,
            'status' => \App\Enums\OrderStatus::Paid,
        ]);

        $order->payment?->update([
            'status' => PaymentStatus::Paid,
            'stripe_payment_intent_id' => $paymentIntentId ?? $order->payment?->stripe_payment_intent_id,
        ]);
    }

    public function handleWebhook(string $payload, ?string $signature): void
    {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret) {
            throw new \RuntimeException('Stripe webhook secret not configured.');
        }

        $event = Webhook::constructEvent($payload, $signature, $secret);

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $payment = Payment::where('stripe_payment_intent_id', $intent->id)->first();

            if ($payment) {
                $this->markPaid($payment->order, $intent->id);
            }
        }
    }
}
