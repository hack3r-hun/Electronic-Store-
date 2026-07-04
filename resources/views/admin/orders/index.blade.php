@extends('layouts.admin')

@section('page-title', 'Orders')

@section('content')
    <x-reveal type="fade-up">
        <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">
            <select name="status" class="input-field w-full sm:w-52" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(\App\Enums\OrderStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            @if(request('status'))
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-slate-500 hover:text-brand-700 font-medium transition-colors">Clear filter</a>
            @endif
        </form>
    </x-reveal>

    <x-reveal type="fade-up" delay="80">
        <div class="admin-card">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="group">
                                <td class="font-semibold text-slate-900 group-hover:text-brand-700 transition-colors">{{ $order->order_number }}</td>
                                <td>{{ $order->user?->name ?? 'Guest' }}</td>
                                <td class="font-bold text-slate-900">{{ shop_money($order->total) }}</td>
                                <td>
                                    <span class="admin-badge bg-slate-100 text-slate-700">{{ $order->payment_method->label() }}</span>
                                </td>
                                <td>
                                    <span class="admin-badge capitalize
                                        @if($order->status->value === 'delivered') bg-green-100 text-green-700
                                        @elseif(in_array($order->status->value, ['pending', 'awaiting_cod'])) bg-brand-100 text-brand-700
                                        @elseif($order->status->value === 'cancelled') bg-red-100 text-red-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                        {{ $order->status->label() }}
                                    </span>
                                </td>
                                <td class="text-slate-500">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="admin-action-link">View →</a>
                                        <x-confirm-delete
                                            :action="route('admin.orders.destroy', $order)"
                                            title="Delete order?"
                                            :item="$order->order_number"
                                            message="The order and its items will be permanently deleted. Stock for its products will be returned unless the order was already cancelled."
                                        >
                                            <span class="sr-only">Delete</span>
                                        </x-confirm-delete>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-16 text-slate-500">
                                    <x-icon name="cart" class="w-14 h-14 mx-auto mb-3 text-slate-300" />
                                    No orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-reveal>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
