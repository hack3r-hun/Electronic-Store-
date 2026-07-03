@props(['product'])

<article class="group bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden card-glow card-hover h-full flex flex-col">
    <a href="{{ route('products.show', $product->slug) }}" class="block relative overflow-hidden aspect-square bg-gradient-to-br from-slate-50 to-slate-100">
        <img
            src="{{ $product->image_url }}"
            alt="{{ $product->name }}"
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
        @if($product->is_on_sale)
            <span class="absolute top-3 left-3 bg-brand-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-lg">Sale</span>
        @endif
        @if($product->is_featured)
            <span class="absolute top-3 right-3 bg-brand-600 text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-lg">Featured</span>
        @elseif(!$product->is_in_stock)
            <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2.5 py-1 rounded-lg">Out of Stock</span>
        @endif
        <div class="absolute bottom-3 left-3 right-3 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
            <span class="block text-center text-xs font-semibold text-white bg-brand-600/90 backdrop-blur-sm py-2 rounded-lg">View Details</span>
        </div>
    </a>
    <div class="p-5 flex-1 flex flex-col">
        <p class="text-xs font-semibold text-brand-600 mb-1.5 uppercase tracking-wide">{{ $product->category->name }}</p>
        <a href="{{ route('products.show', $product->slug) }}">
            <h3 class="font-semibold text-slate-900 line-clamp-2 group-hover:text-brand-700 transition-colors duration-300">{{ $product->name }}</h3>
        </a>
        <div class="mt-auto pt-4 flex items-center justify-between gap-2">
            <div>
                <span class="text-xl font-bold text-slate-900">{{ shop_money($product->effective_price) }}</span>
                @if($product->is_on_sale)
                    <span class="text-sm text-slate-400 line-through ml-1">{{ shop_money($product->price) }}</span>
                @endif
            </div>
            @if($product->is_in_stock)
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="p-2.5 rounded-xl bg-brand-50 text-brand-700 hover:bg-brand-600 hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-brand-600/20 transition-all duration-300" title="Add to cart">
                        <x-icon name="cart" class="w-5 h-5" />
                    </button>
                </form>
            @endif
        </div>
    </div>
</article>
