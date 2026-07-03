@extends('layouts.storefront')

@section('title', 'Home')

@section('content')
    @php $homeMeta = \App\Models\Page::where('slug', 'home')->first()?->meta ?? []; @endphp

    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-sky-50 via-white to-brand-50 min-h-[80vh] flex items-center border-b border-slate-100">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-brand-100/60 rounded-full blur-3xl animate-pulse-soft"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-brand-200/50 rounded-full blur-3xl animate-float"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <x-reveal type="fade-up">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-brand-100 text-brand-700 text-sm font-semibold mb-6">Trusted Local Store Since 2010</span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-[1.1] mb-6 text-slate-900">
                            {{ $homeMeta['hero_title'] ?? 'Everything Electrical, One Trusted Store' }}
                        </h1>
                        <p class="text-lg text-slate-600 mb-8 leading-relaxed max-w-lg">
                            {{ $homeMeta['hero_subtitle'] ?? 'Bulbs, wiring, capacitors, pipes, bolts & more — delivered to your door.' }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('products.index') }}" class="btn-accent text-base px-8 py-4">{{ $homeMeta['hero_cta'] ?? 'Shop Now' }}</a>
                            <a href="{{ route('about') }}" class="btn-outline">Learn More</a>
                        </div>
                    </x-reveal>
                </div>
                <x-reveal type="fade-left" delay="200" class="hidden lg:block">
                    <div class="relative">
                        <div class="grid grid-cols-2 gap-4">
                            @foreach([
                                ['icon' => 'light-bulb', 'label' => 'Bulbs & Lighting', 'bg' => 'bg-brand-50 border-brand-100'],
                                ['icon' => 'plug', 'label' => 'Wiring & Cables', 'bg' => 'bg-brand-50 border-brand-100'],
                                ['icon' => 'bolt', 'label' => 'Capacitors', 'bg' => 'bg-brand-100 border-brand-200'],
                                ['icon' => 'wrench', 'label' => 'Bolts & Nuts', 'bg' => 'bg-slate-50 border-slate-100'],
                            ] as $i => $card)
                                <div class="p-6 rounded-2xl {{ $card['bg'] }} border card-hover {{ $i % 2 === 1 ? 'mt-8' : '' }}">
                                    <x-icon :name="$card['icon']" class="w-10 h-10 text-brand-600 mb-3" />
                                    <p class="text-sm font-semibold text-slate-800">{{ $card['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-reveal>
            </div>
        </div>
    </section>

    {{-- Trust badges --}}
    <section class="bg-white border-b border-slate-100 relative z-10 -mt-8 mx-4 md:mx-8 lg:mx-auto max-w-6xl rounded-2xl shadow-xl shadow-brand-900/5 border border-slate-100">
        <div class="px-6 py-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => 'shield', 'title' => 'Genuine Products', 'desc' => 'Quality guaranteed'],
                    ['icon' => 'truck', 'title' => 'Fast Delivery', 'desc' => 'City-wide shipping'],
                    ['icon' => 'banknotes', 'title' => 'Cash on Delivery', 'desc' => 'Pay when you receive'],
                    ['icon' => 'phone', 'title' => 'Local Support', 'desc' => 'Call us anytime'],
                ] as $i => $badge)
                    <x-reveal type="fade-up" :delay="$i * 80">
                        <x-store-card class="text-center h-full flex flex-col items-center justify-center">
                            <div class="w-12 h-12 mb-3 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center group-hover:scale-110 transition-all duration-300">
                                <x-icon :name="$badge['icon']" class="w-6 h-6" />
                            </div>
                            <h3 class="font-semibold text-slate-900">{{ $badge['title'] }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ $badge['desc'] }}</p>
                        </x-store-card>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="py-20 bg-slate-50 bg-mesh">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">Simple Process</span>
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle mx-auto">Order in 3 easy steps — no confusion, no hassle.</p>
            </x-reveal>
            <div class="store-grid-3">
                @foreach([
                    ['step' => '01', 'icon' => 'search', 'title' => 'Browse & Choose', 'desc' => 'Search products by category or name. Find bulbs, wiring, pipes, and more.'],
                    ['step' => '02', 'icon' => 'cart', 'title' => 'Add to Cart', 'desc' => 'Select quantity and add items. Guest checkout or create an account.'],
                    ['step' => '03', 'icon' => 'truck', 'title' => 'Get Delivered', 'desc' => 'Pay with COD or card. We deliver to your doorstep quickly.'],
                ] as $i => $step)
                    <x-reveal type="fade-up" :delay="$i * 120">
                        <x-store-card class="relative text-center h-full card-glow">
                            <span class="absolute top-4 right-4 text-4xl font-black text-brand-100">{{ $step['step'] }}</span>
                            <div class="icon-box mx-auto mb-5 bg-brand-50 text-brand-600">
                                <x-icon :name="$step['icon']" class="w-7 h-7" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $step['title'] }}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ $step['desc'] }}</p>
                        </x-store-card>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Categories --}}
    @if($categories->isNotEmpty())
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-12">
                <span class="section-badge">Categories</span>
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle mx-auto">Find exactly what you need — bulbs, wiring, pipes, and more</p>
            </x-reveal>
            <div class="store-grid-6">
                @foreach($categories as $i => $category)
                    <x-reveal type="scale" :delay="($i % 6) * 60">
                        <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                           class="group block bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden card-glow card-hover">
                            <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                            </div>
                            <div class="p-4 text-center">
                                <h3 class="font-semibold text-slate-900 text-sm group-hover:text-brand-700 transition-colors">{{ $category->name }}</h3>
                                <p class="text-xs text-slate-500 mt-1">{{ $category->active_products_count }} items</p>
                            </div>
                        </a>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Products --}}
    @if($featuredProducts->isNotEmpty())
    <section class="py-20 bg-gradient-to-b from-brand-50/50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <span class="section-badge">Hot Deals</span>
                    <h2 class="section-title">Featured Products</h2>
                    <p class="section-subtitle">Best deals picked for you</p>
                </div>
                <a href="{{ route('products.index') }}" class="text-brand-700 font-semibold hover:text-brand-800 transition-colors flex items-center gap-1 group">
                    View all <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </x-reveal>
            <div class="store-grid-4">
                @foreach($featuredProducts as $i => $product)
                    <x-reveal type="fade-up" :delay="($i % 4) * 80">
                        <x-product-card :product="$product" />
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Testimonials --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">Testimonials</span>
                <h2 class="section-title">What Our Customers Say</h2>
            </x-reveal>
            <div class="store-grid-3">
                @foreach([
                    ['name' => 'Rashid Ahmed', 'role' => 'Electrician', 'text' => 'Best place for genuine wiring and capacitors. Fair prices and they always have stock when I need it.', 'stars' => 5],
                    ['name' => 'Fatima Bibi', 'role' => 'Homeowner', 'text' => 'Ordered LED bulbs online with COD. Delivered next day. Very happy with the quality!', 'stars' => 5],
                    ['name' => 'Imran Contractor', 'role' => 'Builder', 'text' => 'I buy pipes, bolts, and fittings in bulk. Reliable store, honest people. Highly recommended.', 'stars' => 5],
                ] as $i => $review)
                    <x-reveal type="fade-up" :delay="$i * 100">
                        <div class="h-full p-8 bg-slate-50 rounded-2xl border border-slate-100 card-hover relative">
                            <div class="text-brand-400 mb-4 flex gap-0.5 justify-center">
                                @for($s = 0; $s < $review['stars']; $s++)
                                    <x-icon name="star" class="w-5 h-5" />
                                @endfor
                            </div>
                            <p class="text-slate-600 leading-relaxed italic">"{{ $review['text'] }}"</p>
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <p class="font-semibold text-slate-900">{{ $review['name'] }}</p>
                                <p class="text-sm text-brand-600">{{ $review['role'] }}</p>
                            </div>
                        </div>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest Products --}}
    @if($latestProducts->isNotEmpty())
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <span class="section-badge">Just Arrived</span>
                    <h2 class="section-title">New Arrivals</h2>
                    <p class="section-subtitle">Fresh stock just added</p>
                </div>
                <a href="{{ route('products.index', ['sort' => 'latest']) }}" class="text-brand-700 font-semibold hover:text-brand-800 transition-colors flex items-center gap-1 group">
                    View all <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
            </x-reveal>
            <div class="store-grid-4">
                @foreach($latestProducts as $i => $product)
                    <x-reveal type="fade-up" :delay="($i % 4) * 80">
                        <x-product-card :product="$product" />
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal type="scale">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-100 via-sky-50 to-brand-50 border border-brand-100 p-10 md:p-16 text-center">
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900">Need Help Choosing?</h2>
                    <p class="text-slate-600 mb-8 max-w-xl mx-auto text-lg">Our team knows every product on the shelf. Call or message us — we're happy to help.</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', shop_config('contact_phone')) }}" class="btn-outline inline-flex items-center gap-2">
                            <x-icon name="phone" class="w-5 h-5" /> {{ shop_config('contact_phone') }}
                        </a>
                    </div>
                </div>
            </div>
        </x-reveal>
    </section>
@endsection
