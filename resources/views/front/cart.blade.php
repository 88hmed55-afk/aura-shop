@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">{{ __('app.shopping_cart') }}</h1>
            <p class="text-xs text-gray-400 mt-1">Review your luxury selections before proceeding to secure concierge checkout.</p>
        </div>

        @if($cartItems->isEmpty())
            <div class="glass-panel rounded-3xl p-16 text-center text-gray-400 max-w-lg mx-auto space-y-4">
                <div class="w-20 h-20 rounded-full bg-gray-800 flex items-center justify-center text-3xl mx-auto text-amber-500">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <h3 class="text-lg font-bold text-white">{{ __('app.empty_cart') }}</h3>
                <a href="{{ route('shop') }}" class="inline-block px-8 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-bold text-xs uppercase rounded-2xl hover:from-amber-400 hover:to-amber-500 transition shadow-lg shadow-amber-500/20">
                    {{ __('app.continue_shopping') }}
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Cart Items Table (8 cols) -->
                <div class="lg:col-span-8 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="glass-panel p-4 sm:p-6 rounded-3xl border border-gray-200 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <img src="{{ $item->product ? $item->product->main_image : '' }}" class="w-20 h-20 rounded-2xl object-cover border border-gray-700 shrink-0">
                                <div>
                                    <span class="text-[10px] text-amber-400 font-bold uppercase">{{ $item->product && $item->product->category ? $item->product->category->name : '' }}</span>
                                    <h3 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-1">
                                        <a href="{{ route('product.show', $item->product->slug) }}">{{ $item->product ? $item->product->name : '' }}</a>
                                    </h3>
                                    <p class="text-amber-500 font-black text-sm mt-1">${{ number_format($item->product->price, 2) }}</p>
                                </div>
                            </div>

                            <!-- Quantity Modifier Form -->
                            <div class="flex items-center gap-6 w-full sm:w-auto justify-between">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" onchange="this.form.submit()"
                                           class="w-16 px-3 py-1.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs font-bold text-center">
                                </form>

                                <div class="text-right">
                                    <span class="text-xs text-gray-400 block">{{ __('app.subtotal') }}</span>
                                    <span class="text-base font-black text-gray-900 dark:text-white">${{ number_format($item->subtotal, 2) }}</span>
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-500 transition">
                                        <i class="fa-solid fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Card (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                        <h3 class="font-bold text-base text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-800 pb-4">
                            {{ __('app.order_summary') }}
                        </h3>

                        <!-- Coupon Form -->
                        <form action="{{ route('cart.coupon') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="code" placeholder="{{ __('app.coupon_code') }}" required
                                   class="flex-1 px-4 py-2.5 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs font-bold uppercase focus:outline-none focus:border-amber-500">
                            <button type="submit" class="px-4 py-2.5 bg-amber-500/20 text-amber-500 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs rounded-xl transition">
                                {{ __('app.apply_coupon') }}
                            </button>
                        </form>

                        @if(session('coupon_code'))
                            <div class="flex items-center justify-between text-xs p-3 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                                <span>Code <strong>{{ session('coupon_code') }}</strong> active</span>
                                <form action="{{ route('cart.coupon.clear') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:underline">Remove</button>
                                </form>
                            </div>
                        @endif

                        <!-- Summary Breakdown -->
                        <div class="space-y-3 text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>{{ __('app.subtotal') }}</span>
                                <span class="font-bold text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-500 font-bold">
                                    <span>{{ __('app.discount') }}</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>{{ __('app.tax') }} (15% VAT)</span>
                                <span class="font-bold text-gray-900 dark:text-white">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('app.shipping') }}</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $shipping == 0 ? __('app.free') : '$' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-black text-gray-900 dark:text-white pt-4 border-t border-gray-200 dark:border-gray-800">
                                <span>{{ __('app.total') }}</span>
                                <span class="text-amber-500 text-xl">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="block w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-extrabold text-xs text-center uppercase tracking-wider rounded-2xl hover:from-amber-400 hover:to-amber-500 transition shadow-xl shadow-amber-500/20">
                            {{ __('app.proceed_to_checkout') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
