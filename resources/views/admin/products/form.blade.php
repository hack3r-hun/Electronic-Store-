@extends('layouts.admin')

@section('page-title', $product->exists ? 'Edit Product' : 'Add Product')

@section('content')
    <form action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @if($product->exists) @method('PUT') @endif

        <div class="bg-white rounded-2xl border border-slate-100 shadow-card p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-2">Category</label>
                <select name="category_id" class="input-field" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input-field" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="input-field" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stock</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" class="input-field" min="0" required>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="input-field" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="input-field">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="4" class="input-field">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Images</label>
                @if($product->exists && $product->images->isNotEmpty())
                    <div class="flex flex-wrap gap-3 mb-3">
                        @foreach($product->images as $image)
                            <img src="{{ $image->url }}" alt="" class="w-20 h-20 object-cover rounded-xl border border-slate-100">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" class="input-field">
                <p class="text-xs text-slate-500 mt-1">Upload one or more product photos (JPG, PNG, max 2MB each)</p>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="rounded text-brand-600"> Active</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="rounded text-brand-600"> Featured</label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn-outline">Cancel</a>
        </div>
    </form>
@endsection
