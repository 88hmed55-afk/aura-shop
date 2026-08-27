@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('app.wishlist') }}</h1>
            <p class="text-xs text-gray-400 mt-1">Your curated collection of favorite luxury items.</p>
        </div>

        @if($wishlists->isEmpty())
            <div class="glass-panel rounded-3xl p-16 text-center text-gray-400 max-w-lg mx-auto space-y-4">
                <div class="w-20 h-20 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center text-3xl mx-auto">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Your Wishlist is Empty</h3>
                <p class="text-xs text-gray-400">Save items you love by clicking the heart icon while exploring our catalog.</p>
                <a href="{{ route('shop') }}" class="inline-block px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-bold text-xs uppercase rounded-2xl hover:from-amber-400 hover:to-amber-500 transition shadow-lg shadow-amber-500/20">
                    {{ __('app.explore_collection') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($wishlists as $wish)
                    @if($wish->product)
                        <div class="group glass-panel rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-800 hover:border-amber-500/40 transition duration-300 flex flex-col justify-between"
                             x-data="{ adding: false }">
                            <div class="relative overflow-hidden aspect-square">
                                <img src="{{ $wish->product->main_image }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <button @click="
                                    fetch('{{ route('wishlist.toggle') }}', {
                                        method: 'POST',
                                        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                        body: JSON.stringify({ product_id: {{ $wish->product->id }} })
                                    }).then(() => window.location.reload())"
                                    class="absolute top-3 right-3 w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs shadow-lg">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>

                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] text-amber-400 font-bold uppercase">{{ $wish->product->category ? $wish->product->category->name : '' }}</span>
                                    <a href="{{ route('product.show', $wish->product->slug) }}" class="font-bold text-sm text-gray-900 dark:text-white hover:text-amber-400 transition block truncate mt-1">
                                        {{ $wish->product->name }}
                                    </a>
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-200 dark:border-gray-800">
                                    <span class="text-lg font-black text-amber-500">${{ number_format($wish->product->price, 2) }}</span>
                                    <button @click="
                                        adding = true;
                                        fetch('{{ route('cart.add') }}', {
                                            method: 'POST',
                                            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                            body: JSON.stringify({ product_id: {{ $wish->product->id }}, quantity: 1 })
                                        }).then(r => r.json()).then(res => {
                                            adding = false;
                                            $dispatch('cart-updated', { count: res.drawer.count });
                                            $dispatch('toast', { message: res.message, type: 'success' });
                                        });"
                                        class="px-3.5 py-2 rounded-xl bg-amber-500/10 text-amber-500 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs transition">
                                        <span x-text="adding ? '...' : '{{ __('app.add_to_cart') }}'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
