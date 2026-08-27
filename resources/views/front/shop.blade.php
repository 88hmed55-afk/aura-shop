@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header Banner -->
        <div class="mb-10 text-center max-w-2xl mx-auto">
            <span class="text-xs font-bold text-amber-500 tracking-widest uppercase">{{ __('app.brand_name') }} CATALOG</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mt-2">{{ __('app.shop') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Explore our complete collection of certified luxury timepieces, audio, fragrances, and accessories.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Filter -->
            <div class="lg:col-span-1 space-y-6">
                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                        <h3 class="font-bold text-base text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-amber-500"></i> Filters
                        </h3>
                        <a href="{{ route('shop') }}" class="text-xs text-amber-500 hover:underline font-semibold">{{ __('app.clear_filters') }}</a>
                    </div>

                    <form action="{{ route('shop') }}" method="GET" class="space-y-6">
                        <!-- Retain search input if present -->
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        <!-- Categories -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">{{ __('app.categories') }}</label>
                            <div class="space-y-2 max-h-56 overflow-y-auto custom-scrollbar">
                                <a href="{{ route('shop', array_merge(request()->except('category', 'page'), [])) }}"
                                   class="flex items-center justify-between text-xs px-3 py-2 rounded-xl transition {{ !request('category') ? 'bg-amber-500 text-slate-950 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800' }}">
                                    <span>{{ __('app.all_categories') }}</span>
                                </a>
                                @foreach($categories as $cat)
                                    <a href="{{ route('shop', array_merge(request()->except('category', 'page'), ['category' => $cat->slug])) }}"
                                       class="flex items-center justify-between text-xs px-3 py-2 rounded-xl transition {{ request('category') === $cat->slug ? 'bg-amber-500 text-slate-950 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800' }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-[10px] opacity-70">({{ $cat->products_count }})</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">{{ __('app.filter_by_price') }}</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] text-gray-400 block mb-1">{{ __('app.min_price') }}</span>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0"
                                           class="w-full px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 block mb-1">{{ __('app.max_price') }}</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="{{ (int)$maxPriceLimit }}"
                                           class="w-full px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                                </div>
                            </div>
                        </div>

                        <!-- Toggles -->
                        <div class="space-y-3 pt-2">
                            <label class="flex items-center gap-3 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                                       class="rounded text-amber-500 focus:ring-amber-500 bg-gray-800 border-gray-700">
                                <span>{{ __('app.in_stock_only') }}</span>
                            </label>
                            <label class="flex items-center gap-3 text-xs text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox" name="featured" value="1" {{ request('featured') ? 'checked' : '' }}
                                       class="rounded text-amber-500 focus:ring-amber-500 bg-gray-800 border-gray-700">
                                <span>{{ __('app.featured_only') }}</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-bold text-xs uppercase rounded-xl hover:from-amber-400 hover:to-amber-500 transition">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            <!-- Products List Area -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Sorting & Stats Top Bar -->
                <div class="glass-panel p-4 rounded-2xl border border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs">
                    <div class="text-gray-500 dark:text-gray-400 font-medium">
                        {{ __('app.showing_results', ['count' => $products->total()]) }}
                    </div>

                    <form action="{{ route('shop') }}" method="GET" class="flex items-center gap-2">
                        @foreach(request()->except('sort') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <span class="text-gray-400 font-bold uppercase">{{ __('app.sort_by') }}:</span>
                        <select name="sort" onchange="this.form.submit()" 
                                class="px-3 py-2 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs font-semibold focus:outline-none focus:border-amber-500">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('app.sort_newest') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('app.sort_price_low') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('app.sort_price_high') }}</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('app.sort_rating') }}</option>
                        </select>
                    </form>
                </div>

                <!-- Products Grid -->
                @if($products->isEmpty())
                    <div class="glass-panel rounded-3xl p-12 text-center text-gray-500 space-y-4">
                        <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 text-2xl mx-auto">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-300">{{ __('app.no_products_found') }}</h3>
                        <a href="{{ route('shop') }}" class="inline-block px-6 py-2.5 bg-amber-500 text-slate-950 font-bold text-xs rounded-xl hover:bg-amber-400 transition">
                            {{ __('app.clear_filters') }}
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="group glass-panel rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-amber-500/40 transition duration-300 flex flex-col justify-between"
                                 x-data="{ adding: false }">
                                <div class="relative overflow-hidden aspect-square">
                                    <img src="{{ $product->main_image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @if($product->compare_at_price)
                                        <span class="absolute top-3 left-3 bg-rose-600 text-white text-[10px] font-extrabold px-2.5 py-1 rounded-full uppercase">
                                            -{{ $product->discount_percentage }}%
                                        </span>
                                    @endif

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

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
