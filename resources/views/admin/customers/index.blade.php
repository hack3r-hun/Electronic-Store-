@extends('layouts.admin')

@section('page-title', 'Customers')

@section('content')
    <x-reveal type="fade-up">
        <x-admin-page-header :count="$customers->total()" count-label="registered customers" />
    </x-reveal>

    <x-reveal type="fade-up" delay="80">
        <div class="admin-card">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Orders</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr class="group">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-sm font-bold group-hover:scale-110 transition-transform duration-300">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-900">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="text-slate-600">{{ $customer->email }}</td>
                                <td class="text-slate-500">{{ $customer->phone ?? '—' }}</td>
                                <td>
                                    <span class="admin-badge bg-indigo-100 text-indigo-700">{{ $customer->orders_count }} orders</span>
                                </td>
                                <td class="text-slate-500">{{ $customer->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16 text-slate-500">
                                    <x-icon name="users" class="w-14 h-14 mx-auto mb-3 text-slate-300" />
                                    No customers registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-reveal>

    <div class="mt-6">{{ $customers->links() }}</div>
@endsection
