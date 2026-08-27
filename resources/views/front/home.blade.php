@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="relative overflow-hidden pt-12 pb-24 lg:pt-20 lg:pb-32 bg-gradient-to-b from-gray-900 via-aura-dark to-aura-dark text-white">
    <!-- Decorative Glow Orbs -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[300px] h-[300px] bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Hero Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-start">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full glass-panel border border-amber-500/30 text-amber-400 text-xs font-semibold tracking-widest uppercase animate-pulse">
                    <i class="fa-solid fa-crown text-xs"></i> AURA EXCLUSIVE SELECTION
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    {{ __('app.hero_title') }}
                </h1>
                <p class="text-base sm:text-lg text-gray-300 max-w-2xl leading-relaxed font-light">
                    {{ __('app.hero_subtitle') }}
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-4">
                    <a href="{{ route('shop') }}" 
                       class="px-8 py-4 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-extrabold text-sm tracking-wider uppercase shadow-xl shadow-amber-500/25 transition transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-bag-shopping {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i>
                        {{ __('app.explore_collection') }}
                    </a>
                    <a href="#featured" 
                       class="px-8 py-4 rounded-2xl glass-panel hover:bg-white/10 text-white font-bold text-sm tracking-wider uppercase border border-gray-700 transition">
                        {{ __('app.view_details') }}
                    </a>
                </div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-3 gap-6 pt-8 border-t border-gray-800/80 max-w-lg mx-auto lg:mx-0">
                    <div>
                        <div class="text-2xl font-black text-amber-400">100%</div>
                        <div class="text-xs text-gray-400 font-medium">Certified Original</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-amber-400">24/7</div>
                        <div class="text-xs text-gray-400 font-medium">Concierge Support</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black text-amber-400">4.95★</div>
                        <div class="text-xs text-gray-400 font-medium">Client Rating</div>
                    </div>
                </div>
            </div>

            <!-- Hero Feature Product Card -->
            <div class="lg:col-span-5">
                @if($heroProducts->count() > 0)
                    @php $heroItem = $heroProducts->first(); @endphp
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-amber-500 to-amber-700 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                        <div class="relative glass-panel rounded-3xl p-6 border border-gray-800 space-y-6">
                            <div class="relative overflow-hidden rounded-2xl aspect-square">
                                <img src="{{ $heroItem->main_image }}" alt="{{ $heroItem->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <span class="absolute top-3 right-3 bg-amber-500 text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase">
                                    Featured Masterpiece
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-amber-400 font-semibold tracking-widest uppercase">{{ $heroItem->category ? $heroItem->category->name : '' }}</span>
                                <h3 class="text-xl font-bold text-white mt-1">{{ $heroItem->name }}</h3>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-2">{{ $heroItem->short_description }}</p>
                            </div>
                            <div class="flex items-center justify-between pt-2 border-t border-gray-800">
                                <div>
                                    <span class="text-2xl font-black text-amber-400">${{ number_format($heroItem->price, 2) }}</span>
                                    @if($heroItem->compare_at_price)
                                        <span class="text-xs text-gray-500 line-through ml-2">${{ number_format($heroItem->compare_at_price, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('product.show', $heroItem->slug) }}" class="px-5 py-2.5 rounded-xl bg-amber-500/10 text-amber-400 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs transition">
                                    Acquire Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Shop By Category Section -->
<section class="py-16 bg-gray-100 dark:bg-gray-900/50">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
            <div>
                <span class="text-xs font-bold text-amber-500 tracking-widest uppercase">{{ __('app.categories') }}</span>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">{{ __('app.shop_by_category') }}</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-xs font-bold text-amber-500 hover:text-amber-400 uppercase tracking-wider flex items-center gap-2">
                View Catalog <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach($featuredCategories as $category)
                <a href="{{ route('shop', ['category' => $category->slug]) }}" 
                   class="group relative overflow-hidden rounded-2xl aspect-[4/5] glass-panel border border-gray-800 shadow-md hover:border-amber-500/50 transition duration-300">
                    <img src="{{ $category->image }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/40 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 text-white space-y-1">
                        <h3 class="font-bold text-base group-hover:text-amber-400 transition">{{ $category->name }}</h3>
                        <p class="text-xs text-gray-400 font-medium">{{ $category->products_count }} items</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Masterpieces Grid -->
<section id="featured" class="py-20">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="text-xs font-bold text-amber-500 tracking-widest uppercase">{{ __('app.brand_name') }} Collection</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mt-2">{{ __('app.featured_products') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Handcrafted luxury items curated for discerning collectors and taste-makers.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($newArrivals as $product)
                <div class="group glass-panel rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-amber-500/40 transition duration-300 flex flex-col justify-between"
                     x-data="{ adding: false }">
                    <div class="relative overflow-hidden aspect-square">
                        <img src="{{ $product->main_image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @if($product->compare_at_price)
                            <span class="absolute top-3 left-3 bg-rose-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                -{{ $product->discount_percentage }}%
                            </span>
                        @endif

                        <!-- Hover Actions -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center gap-3">
                            <a href="{{ route('product.show', $product->slug) }}" class="w-10 h-10 rounded-full bg-white text-slate-950 flex items-center justify-center hover:bg-amber-500 transition">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <button @click="
                                fetch('{{ route('wishlist.toggle') }}', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                    body: JSON.stringify({ product_id: {{ $product->id }} })
                                }).then(r => r.json()).then(res => {
                                    if(res.redirect) window.location.href = res.redirect;
                                    else $dispatch('toast', { message: res.message, type: 'success' });
                                })"
                                class="w-10 h-10 rounded-full bg-white text-slate-950 flex items-center justify-center hover:bg-rose-500 hover:text-white transition">
                                <i class="fa-solid fa-heart text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-400 mb-1">
                                <span>{{ $product->category ? $product->category->name : '' }}</span>
                                <span class="text-amber-400 font-bold"><i class="fa-solid fa-star text-[10px]"></i> {{ number_format($product->rating, 1) }}</span>
                            </div>
                            <a href="{{ route('product.show', $product->slug) }}" class="font-bold text-sm text-gray-900 dark:text-white hover:text-amber-400 transition line-clamp-1">
                                {{ $product->name }}
                            </a>
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-800">
                            <div>
                                <span class="text-lg font-black text-amber-500">${{ number_format($product->price, 2) }}</span>
                                @if($product->compare_at_price)
                                    <span class="text-xs text-gray-400 line-through ml-1.5">${{ number_format($product->compare_at_price, 2) }}</span>
                                @endif
                            </div>
                            <button @click="
                                adding = true;
                                fetch('{{ route('cart.add') }}', {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                    body: JSON.stringify({ product_id: {{ $product->id }}, quantity: 1 })
                                }).then(r => r.json()).then(res => {
                                    adding = false;
                                    $dispatch('cart-updated', { count: res.drawer.count });
                                    $dispatch('toast', { message: res.message, type: 'success' });
                                });"
                                class="px-3.5 py-2 rounded-xl bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs transition flex items-center gap-1.5">
                                <i class="fa-solid fa-cart-plus" x-show="!adding"></i>
                                <span x-text="adding ? '...' : '{{ __('app.add_to_cart') }}'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Distinction Pillars -->
<section class="py-16 bg-gray-950 border-y border-gray-800 text-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-plane-departure"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-white mb-1">{{ __('app.free_express_shipping') }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ __('app.free_shipping_desc') }}</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-white mb-1">{{ __('app.authenticity_guarantee') }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ __('app.authenticity_desc') }}</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-white mb-1">{{ __('app.white_glove_support') }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ __('app.white_glove_desc') }}</p>
                </div>
            </div>
            <div class="flex items-start gap-4 p-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-white mb-1">{{ __('app.secure_payment') }}</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">{{ __('app.secure_payment_desc') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
