@extends('layouts.admin')

@section('page-title', 'Products')

@section('content')
    <x-reveal type="fade-up">
        <x-admin-page-header :count="$products->total()" count-label="products">
            <x-slot:action>
                <a href="{{ route('admin.products.create') }}" class="btn-primary !py-2.5 !px-5 text-sm">+ Add Product</a>
            </x-slot:action>
        </x-admin-page-header>
    </x-reveal>

    <x-reveal type="fade-up" delay="80">
        <div class="admin-card">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="group">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->image_url }}" alt="" class="w-10 h-10 rounded-xl object-cover border border-slate-100 group-hover:scale-110 transition-transform duration-300">
                                        <span class="font-semibold text-slate-900 group-hover:text-brand-700 transition-colors">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="text-slate-500 font-mono text-xs">{{ $product->sku }}</td>
                                <td class="font-semibold text-slate-900">{{ shop_money($product->effective_price) }}</td>
                                <td>
                                    <span class="admin-badge {{ $product->stock_quantity <= $product->low_stock_threshold ? 'bg-brand-100 text-brand-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $product->stock_quantity }} units
                                    </span>
                                </td>
                                <td>
                                    <span class="admin-badge {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-action-link">Edit →</a>
                                    <x-confirm-delete
                                        :action="route('admin.products.destroy', $product)"
                                        title="Delete this product?"
                                        :item="$product->name"
                                        message="It will be permanently removed from your catalog. This action cannot be undone."
                                    />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 text-slate-500">
                                    <x-icon name="cube" class="w-14 h-14 mx-auto mb-3 text-slate-300" />
                                    No products yet. <a href="{{ route('admin.products.create') }}" class="text-brand-600 font-semibold hover:underline">Add your first product</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </x-reveal>

    <div class="mt-6">{{ $products->links() }}</div>
@endsection
