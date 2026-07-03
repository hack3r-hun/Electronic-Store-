@extends('layouts.storefront')

@section('title', 'About Us')
@section('meta_description', 'Learn about '.shop_name().' — your trusted local electronics and hardware store serving the community since 2010.')

@section('content')
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-sky-50 via-white to-brand-50 py-20 md:py-28 border-b border-slate-100">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-reveal type="fade-up">
                <span class="section-badge">About {{ shop_name() }}</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-slate-900">
                    {{ $page->meta['hero_title'] ?? 'Serving Our Community Since 2010' }}
                </h1>
                <p class="text-lg md:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                    {{ $page->meta['hero_subtitle'] ?? 'Quality products, honest prices, trusted by locals.' }}
                </p>
            </x-reveal>
        </div>
    </section>

    {{-- Our Story --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <x-reveal type="fade-right">
                    <span class="section-badge">Our Story</span>
                    <h2 class="section-title">From a Small Shop to Your Trusted Store</h2>
                    <div class="mt-6 prose prose-lg prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! $page->content ?? '<p>Welcome to our store.</p>' !!}
                    </div>
                </x-reveal>
                <x-reveal type="fade-left" delay="150">
                    <div class="relative">
                        <div class="aspect-[4/3] rounded-3xl overflow-hidden shadow-soft border border-slate-100">
                            <img src="{{ asset('storage/about/store.jpg') }}" alt="{{ shop_name() }} store" class="w-full h-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=900&h=600&fit=crop'">
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-brand-700 text-white px-6 py-4 rounded-2xl shadow-xl font-bold text-lg card-hover">
                            Est. 2010
                        </div>
                    </div>
                </x-reveal>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-16 bg-slate-50 bg-mesh">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['count' => 10, 'suffix' => '+', 'label' => 'Years Experience'],
                    ['count' => 500, 'suffix' => '+', 'label' => 'Products in Stock'],
                    ['count' => 1000, 'suffix' => '+', 'label' => 'Happy Customers'],
                    ['count' => 50, 'suffix' => '+', 'label' => 'Local Partners'],
                ] as $i => $stat)
                    <x-reveal type="scale" :delay="$i * 100">
                        <div class="text-center p-8 bg-white rounded-2xl border border-slate-100 shadow-card card-glow card-hover">
                            <div class="text-4xl md:text-5xl font-bold text-gradient" data-count="{{ $stat['count'] }}" data-count-suffix="{{ $stat['suffix'] }}">0</div>
                            <div class="mt-2 text-slate-600 font-medium">{{ $stat['label'] }}</div>
                        </div>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Mission, Vision, Values --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">What We Stand For</span>
                <h2 class="section-title">Mission, Vision & Values</h2>
            </x-reveal>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach([
                    ['icon' => 'bolt', 'title' => 'Our Mission', 'desc' => 'To provide genuine electrical and hardware products at fair prices, with friendly service that locals can trust every day.', 'bg' => 'bg-brand-100', 'color' => 'text-brand-600'],
                    ['icon' => 'eye', 'title' => 'Our Vision', 'desc' => 'To be the first choice for every home, electrician, and contractor in our city — known for quality, honesty, and reliability.', 'bg' => 'bg-brand-100', 'color' => 'text-brand-700'],
                    ['icon' => 'shield', 'title' => 'Our Values', 'desc' => 'Honesty in pricing, quality in products, respect for customers, and commitment to our community. No shortcuts, ever.', 'bg' => 'bg-brand-100', 'color' => 'text-brand-600'],
                ] as $i => $item)
                    <x-reveal type="fade-up" :delay="$i * 120">
                        <div class="group h-full p-8 rounded-3xl border border-slate-100 bg-white shadow-card card-glow card-hover text-center">
                            <div class="icon-box mx-auto mb-5 {{ $item['bg'] }} {{ $item['color'] }}">
                                <x-icon :name="$item['icon']" class="w-7 h-7" />
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-brand-700 transition-colors">{{ $item['title'] }}</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-20 bg-gradient-to-b from-brand-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">Why Choose Us</span>
                <h2 class="section-title">What Makes Us Different</h2>
                <p class="section-subtitle mx-auto">We are not just a shop — we are your local partner for every project.</p>
            </x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => 'check-circle', 'title' => 'Genuine Products', 'desc' => 'Every item is sourced from trusted suppliers. No fakes, no compromises.'],
                    ['icon' => 'currency', 'title' => 'Fair Local Prices', 'desc' => 'Competitive rates written clearly — no hidden charges or surprises.'],
                    ['icon' => 'truck', 'title' => 'Fast Delivery', 'desc' => 'City-wide delivery with Cash on Delivery available for your convenience.'],
                    ['icon' => 'wrench', 'title' => 'Expert Advice', 'desc' => 'Our staff knows the products. Ask us — we help you pick the right item.'],
                    ['icon' => 'cube', 'title' => 'Wide Selection', 'desc' => 'Bulbs, capacitors, wiring, pipes, bolts, nuts, tools — all under one roof.'],
                    ['icon' => 'users', 'title' => 'Trusted by Locals', 'desc' => 'Electricians, contractors, and families have relied on us for over a decade.'],
                ] as $i => $feature)
                    <x-reveal type="fade-up" :delay="($i % 3) * 100">
                        <div class="group flex gap-4 p-6 bg-white rounded-2xl border border-slate-100 shadow-card card-hover">
                            <div class="shrink-0 w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                                <x-icon :name="$feature['icon']" class="w-6 h-6" />
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 group-hover:text-brand-700 transition-colors">{{ $feature['title'] }}</h3>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">{{ $feature['desc'] }}</p>
                            </div>
                        </div>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Timeline --}}
    <section class="py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">Our Journey</span>
                <h2 class="section-title">How We Grew</h2>
            </x-reveal>
            <div class="relative border-l-2 border-brand-200 ml-4 space-y-10">
                @foreach([
                    ['year' => '2010', 'title' => 'Store Opened', 'desc' => 'Started as a small shop on Main Bazaar with bulbs and basic wiring.'],
                    ['year' => '2015', 'title' => 'Expanded Catalog', 'desc' => 'Added capacitors, pipes, bolts, and hardware for contractors.'],
                    ['year' => '2020', 'title' => 'Online Ordering', 'desc' => 'Launched delivery service so customers can order from home.'],
                    ['year' => 'Today', 'title' => '500+ Products', 'desc' => 'Serving thousands of customers with COD and card payments.'],
                ] as $i => $milestone)
                    <x-reveal type="fade-left" :delay="$i * 100" class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-brand-600 ring-4 ring-brand-100"></div>
                        <span class="text-sm font-bold text-brand-600">{{ $milestone['year'] }}</span>
                        <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $milestone['title'] }}</h3>
                        <p class="text-slate-600 text-sm mt-1">{{ $milestone['desc'] }}</p>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Team --}}
    <section class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="fade-up" class="text-center mb-14">
                <span class="section-badge">Our Team</span>
                <h2 class="section-title">People Behind the Counter</h2>
                <p class="section-subtitle mx-auto">Friendly faces who know every product on the shelf.</p>
            </x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['name' => 'Ahmed Khan', 'role' => 'Store Owner', 'photo' => 'ahmed-khan'],
                    ['name' => 'Bilal Hussain', 'role' => 'Sales Manager', 'photo' => 'bilal-hussain'],
                    ['name' => 'Usman Ali', 'role' => 'Inventory Lead', 'photo' => 'usman-ali'],
                    ['name' => 'Sara Malik', 'role' => 'Customer Support', 'photo' => 'sara-malik'],
                ] as $i => $member)
                    <x-reveal type="scale" :delay="$i * 100">
                        <div class="group text-center p-6 bg-white rounded-2xl border border-slate-100 shadow-card card-hover">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-2xl overflow-hidden ring-4 ring-brand-50 group-hover:ring-brand-200 transition-all duration-500">
                                <img src="{{ asset('storage/team/'.$member['photo'].'.jpg') }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member['name']) }}&background=0ea5e9&color=fff&size=200'">
                            </div>
                            <h3 class="font-bold text-slate-900">{{ $member['name'] }}</h3>
                            <p class="text-sm text-brand-600 font-medium mt-1">{{ $member['role'] }}</p>
                        </div>
                    </x-reveal>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-reveal type="scale">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-50 via-sky-50 to-white border border-brand-100 p-12 md:p-16 text-center">
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900">Ready to Shop With Us?</h2>
                        <p class="text-slate-600 mb-8 max-w-xl mx-auto text-lg">Visit our store or browse online — we are here to help with every electrical and hardware need.</p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="{{ route('products.index') }}" class="btn-primary">Browse Products</a>
                            <a href="{{ route('contact') }}" class="btn-outline">Contact Us</a>
                        </div>
                    </div>
                </div>
            </x-reveal>
        </div>
    </section>
@endsection
