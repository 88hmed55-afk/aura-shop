@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('account.orders') }}" class="text-xs text-amber-500 font-bold uppercase hover:underline flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> {{ __('app.my_orders') }}
            </a>
            <a href="{{ route('invoice.show', $order->order_number) }}" target="_blank" class="px-4 py-2 rounded-xl bg-gray-800 text-amber-400 font-bold text-[10px] uppercase hover:bg-gray-700 transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> {{ __('app.download_invoice') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Header & Timeline -->
                <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-800 pb-6">
                        <div>
                            <h2 class="text-xl font-bold text-white mb-1">Order #{{ $order->order_number }}</h2>
                            <p class="text-xs text-gray-400">Placed on {{ $order->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-xs font-extrabold uppercase bg-{{ $order->status_badge_color }}-500/20 text-{{ $order->status_badge_color }}-400 border border-{{ $order->status_badge_color }}-500/30">
                            {{ $order->status }}
                        </span>
                    </div>

                    <!-- Status Timeline -->
                    <div class="relative pt-4">
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-800 rounded-full"></div>
                        @php
                            $statuses = ['pending', 'processing', 'shipped', 'delivered'];
                            $currentIndex = array_search($order->status, $statuses);
                            if ($currentIndex === false) $currentIndex = -1; // e.g. cancelled
                        @endphp
                        
                        <div class="relative z-10 flex justify-between">
                            @foreach(['Pending', 'Processing', 'Shipped', 'Delivered'] as $index => $label)
                                @php
                                    $isCompleted = $currentIndex >= $index;
                                    $isActive = $currentIndex == $index;
                                    $isCancelled = $order->status === 'cancelled';
                                @endphp
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center border-4 border-gray-900 {{ $isCompleted && !$isCancelled ? 'bg-amber-500 text-slate-950' : 'bg-gray-700 text-gray-500' }} transition-colors duration-500">
                                        @if($isCompleted && !$isCancelled)
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        @else
                                            <span class="text-[10px] font-bold">{{ $index + 1 }}</span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider {{ $isCompleted && !$isCancelled ? 'text-amber-500' : 'text-gray-500' }}">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white border-b border-gray-800 pb-4 mb-6">Items Ordered</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $item->image }}" class="w-16 h-16 rounded-xl object-cover border border-gray-700 shrink-0">
                                    <div>
                                        <h4 class="font-bold text-sm text-white line-clamp-1">
                                            @if($item->product)
                                                <a href="{{ route('product.show', $item->product->slug) }}" class="hover:text-amber-500">{{ $item->product_name }}</a>
                                            @else
                                                {{ $item->product_name }}
                                            @endif
                                        </h4>
                                        <p class="text-xs text-gray-400 mt-1">Qty: {{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-amber-500">${{ number_format($item->total, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary & Details -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Financial Summary -->
                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white border-b border-gray-800 pb-4 mb-4">Payment Summary</h3>
                    
                    <div class="space-y-3 text-xs text-gray-400">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="text-white font-medium">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-emerald-400">
                                <span>Discount @if($order->coupon_code) ({{ $order->coupon_code }}) @endif</span>
                                <span>-${{ number_format($order->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span>Shipping (Express)</span>
                            <span class="text-white font-medium">${{ number_format($order->shipping_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>VAT (15%)</span>
                            <span class="text-white font-medium">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-black text-white pt-3 border-t border-gray-800">
                            <span>Total</span>
                            <span class="text-amber-500">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 rounded-xl bg-gray-900 border border-gray-800 flex items-center justify-between text-xs">
                        <span class="text-gray-400 uppercase font-bold tracking-wider">Method</span>
                        <div class="flex items-center gap-2 text-white font-bold">
                            @if($order->payment_method === 'card')
                                <i class="fa-solid fa-credit-card text-amber-500"></i> Credit Card
                            @elseif($order->payment_method === 'apple_pay')
                                <i class="fa-brands fa-apple-pay text-amber-500 text-lg"></i> Apple Pay
                            @else
                                <i class="fa-solid fa-money-bill text-emerald-500"></i> Cash on Delivery
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800">
                    <h3 class="font-bold text-base text-gray-900 dark:text-white border-b border-gray-800 pb-4 mb-4">Shipping Details</h3>
                    @php $address = $order->shipping_address; @endphp
                    <div class="text-xs text-gray-400 space-y-2">
                        <p class="font-bold text-white">{{ $address['full_name'] ?? $order->customer_name }}</p>
                        <p>{{ $address['phone'] ?? $order->customer_phone }}</p>
                        <p>{{ $address['address_line_1'] ?? '' }}</p>
                        <p>{{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }}</p>
                        <p>{{ $address['country'] ?? '' }}</p>
                    </div>

                    @if($order->tracking_number)
                        <div class="mt-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-center">
                            <span class="block text-[10px] text-amber-500/80 font-bold uppercase mb-1">Tracking Number</span>
                            <span class="font-mono text-amber-400 font-bold tracking-widest">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
