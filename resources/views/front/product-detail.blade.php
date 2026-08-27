@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen" 
     x-data="{ 
         selectedImage: '{{ $product->main_image }}',
         qty: 1,
         adding: false,
         activeTab: 'desc'
     }">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-gray-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-amber-500">{{ __('app.home') }}</a>
            <span>/</span>
            <a href="{{ route('shop') }}" class="hover:text-amber-500">{{ __('app.shop') }}</a>
            <span>/</span>
            @if($product->category)
                <a href="{{ route('shop', ['category' => $product->category->slug]) }}" class="hover:text-amber-500">{{ $product->category->name }}</a>
                <span>/</span>
            @endif
            <span class="text-gray-900 dark:text-gray-300 font-semibold truncate">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-16">
            <!-- Product Gallery (5 cols) -->
            <div class="lg:col-span-6 space-y-4">
                <div class="glass-panel rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 aspect-square relative">
                    <img :src="selectedImage" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-300">
                    @if($product->compare_at_price)
                        <span class="absolute top-4 left-4 bg-rose-600 text-white text-xs font-black px-3 py-1 rounded-full uppercase">
                            -{{ $product->discount_percentage }}% OFF
                        </span>
                    @endif
                </div>

                @if(!empty($product->images) && count($product->images) > 1)
                    <div class="flex gap-4 overflow-x-auto custom-scrollbar pb-2">
                        @foreach($product->images as $img)
                            <button @click="selectedImage = '{{ $img }}'"
                                    :class="{ 'border-amber-500 ring-2 ring-amber-500/30': selectedImage === '{{ $img }}' }"
                                    class="w-20 h-20 rounded-2xl overflow-hidden border border-gray-700 shrink-0 transition">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Specs & Buying Actions (7 cols) -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold uppercase tracking-widest">
                            {{ $product->category ? $product->category->name : '' }}
                        </span>
                        <span class="text-xs text-gray-400 font-mono">{{ __('app.sku') }}: {{ $product->sku }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Rating -->
                    <div class="flex items-center gap-3 mt-3">
                        <div class="flex text-amber-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star {{ $i <= round($product->rating) ? '' : 'opacity-30' }}"></i>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-gray-400">({{ number_format($product->rating, 2) }} / 5 from {{ $product->reviews_count }} reviews)</span>
                    </div>
                </div>

                <!-- Price Box -->
                <div class="p-4 rounded-2xl glass-panel border border-gray-200 dark:border-gray-800 flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-400 font-medium">Price</div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-3xl font-black text-amber-500" x-text="'$' + ({{ $product->price }} * qty).toFixed(2)">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @if($product->compare_at_price)
                                <span class="text-sm text-gray-400 line-through">${{ number_format($product->compare_at_price, 2) }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Stock Status -->
                    <div>
                        @if($product->isLowStock())
                            <span class="px-3 py-1.5 rounded-full bg-amber-500/20 text-amber-400 text-xs font-bold border border-amber-500/30 flex items-center gap-1.5">
                                <i class="fa-solid fa-triangle-exclamation"></i> {{ __('app.low_stock_warning', ['count' => $product->stock_quantity]) }}
                            </span>
                        @elseif($product->isInStock())
                            <span class="px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold border border-emerald-500/30 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-check"></i> {{ __('app.in_stock') }}
                            </span>
                        @else
                            <span class="px-3 py-1.5 rounded-full bg-rose-500/20 text-rose-400 text-xs font-bold border border-rose-500/30">
                                {{ __('app.out_of_stock') }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Short Description -->
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-light">
                    {{ $product->short_description }}
                </p>

                <!-- Quantity & Actions -->
                <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400">{{ __('app.quantity') }}:</span>
                        <div class="flex items-center gap-1 p-1 rounded-xl bg-gray-200 dark:bg-gray-800 border border-gray-300 dark:border-gray-700">
                            <button @click="qty = Math.max(1, qty - 1)" class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 font-bold hover:bg-amber-500 hover:text-slate-950 transition">-</button>
                            <span x-text="qty" class="w-12 text-center text-sm font-bold"></span>
                            <button @click="qty = Math.min({{ $product->stock_quantity }}, qty + 1)" class="w-8 h-8 rounded-lg bg-white dark:bg-gray-700 font-bold hover:bg-amber-500 hover:text-slate-950 transition">+</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button @click="adding = true; addToCart({{ $product->id }}, qty, $dispatch).then(() => { adding = false; });"
                            class="py-4 px-6 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-extrabold text-xs uppercase tracking-wider hover:from-amber-400 hover:to-amber-500 transition shadow-xl shadow-amber-500/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-base" x-show="!adding"></i>
                            <span x-text="adding ? 'Adding...' : '{{ __('app.add_to_cart') }}'"></span>
                        </button>

                        <button @click="
                            fetch('{{ route('wishlist.toggle') }}', {
                                method: 'POST',
                                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                body: JSON.stringify({ product_id: {{ $product->id }} })
                            }).then(r => r.json()).then(res => {
                                if(res.redirect) window.location.href = res.redirect;
                                else $dispatch('toast', { message: res.message, type: 'success' });
                            })"
                            class="py-4 px-6 rounded-2xl glass-panel text-white hover:bg-white/10 font-bold text-xs uppercase border border-gray-700 transition flex items-center justify-center gap-2">
                            <i class="fa-regular fa-heart text-base text-rose-500"></i>
                            <span>{{ __('app.add_to_wishlist') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description & Reviews Tabs -->
        <div class="glass-panel rounded-3xl p-6 lg:p-8 border border-gray-200 dark:border-gray-800 mb-16">
            <div class="flex border-b border-gray-200 dark:border-gray-800 gap-8 mb-6">
                <button @click="activeTab = 'desc'" 
                        :class="{ 'border-amber-500 text-amber-400 font-bold': activeTab === 'desc', 'text-gray-400 hover:text-white': activeTab !== 'desc' }"
                        class="pb-4 text-sm tracking-wider uppercase border-b-2 transition">
                    {{ __('app.description') }}
                </button>
                <button @click="activeTab = 'reviews'" 
                        :class="{ 'border-amber-500 text-amber-400 font-bold': activeTab === 'reviews', 'text-gray-400 hover:text-white': activeTab !== 'reviews' }"
                        class="pb-4 text-sm tracking-wider uppercase border-b-2 transition">
                    {{ __('app.customer_reviews') }} ({{ $product->reviews->count() }})
                </button>
            </div>

            <!-- Description Tab -->
            <div x-show="activeTab === 'desc'" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-light space-y-4">
                <p>{{ $product->description }}</p>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-cloak class="space-y-8">
                <!-- Submit Review Form -->
                <div class="p-6 rounded-2xl bg-gray-100 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700">
                    <h4 class="font-bold text-base text-gray-900 dark:text-white mb-4">{{ __('app.write_review') }}</h4>
                    <form action="{{ route('product.review', $product->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-400 mb-2 uppercase">{{ __('app.rating') }}</label>
                            <select name="rating" required class="px-4 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs font-bold focus:outline-none">
                                <option value="5">★★★★★ (5/5 Excellence)</option>
                                <option value="4">★★★★☆ (4/5 Very Good)</option>
                                <option value="3">★★★☆☆ (3/5 Average)</option>
                                <option value="2">★★☆☆☆ (2/5 Below Average)</option>
                                <option value="1">★☆☆☆☆ (1/5 Poor)</option>
                            </select>
                        </div>
                        <div>
                            <textarea name="comment" rows="3" required placeholder="Share your experience with this luxury masterpiece..."
                                      class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500"></textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-amber-500 text-slate-950 font-bold text-xs uppercase rounded-xl hover:bg-amber-400 transition">
                            {{ __('app.submit_review') }}
                        </button>
                    </form>
                </div>

                <!-- Reviews Feed -->
                <div class="space-y-4">
                    @forelse($product->reviews as $review)
                        <div class="p-4 rounded-2xl glass-panel border border-gray-800 space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->user ? $review->user->getAvatarUrl() : 'https://ui-avatars.com/api/?name=User' }}" class="w-8 h-8 rounded-full border border-amber-500">
                                    <div>
                                        <h5 class="font-bold text-xs text-white">{{ $review->user ? $review->user->name : 'Anonymous Client' }}</h5>
                                        <div class="text-amber-400 text-[10px]">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-gray-300 font-light leading-relaxed pl-11">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400 italic">No reviews yet for this product. Be the first to share your experience!</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
            <div>
                <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-8">{{ __('app.related_products') }}</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedProducts as $rel)
                        <div class="group glass-panel rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-amber-500/40 transition duration-300">
                            <a href="{{ route('product.show', $rel->slug) }}" class="block aspect-square overflow-hidden">
                                <img src="{{ $rel->main_image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </a>
                            <div class="p-4">
                                <h4 class="font-bold text-xs text-white truncate">{{ $rel->name }}</h4>
                                <div class="text-amber-500 font-black text-sm mt-2">${{ number_format($rel->price, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
