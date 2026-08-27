@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3 space-y-4">
                <div class="glass-panel p-4 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-1 text-xs font-semibold">
                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                        <i class="fa-solid fa-chart-pie"></i> {{ __('app.dashboard') }}
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-amber-500 text-slate-950 font-bold">
                        <i class="fa-solid fa-box-open"></i> {{ __('app.my_orders') }}
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                        <i class="fa-solid fa-location-dot"></i> {{ __('app.my_addresses') }}
                    </a>
                </div>
            </div>

            <div class="lg:col-span-9 space-y-6">
                <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white border-b border-gray-800 pb-4">{{ __('app.my_orders') }}</h3>

                    <div class="space-y-4">
                        @forelse($orders as $order)
                            <div class="p-6 rounded-2xl bg-gray-900/60 border border-gray-800 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-3">
                                        <span class="font-mono font-bold text-amber-400 text-sm">#{{ $order->order_number }}</span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                            {{ $order->status }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-400">Placed on {{ $order->created_at->format('M d, Y') }} • {{ $order->items->count() }} Items</p>
                                </div>

                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <span class="text-[10px] text-gray-400 block uppercase">Total Amount</span>
                                        <span class="text-base font-black text-white">${{ number_format($order->total, 2) }}</span>
                                    </div>
                                    <a href="{{ route('account.order.detail', $order->order_number) }}" class="px-4 py-2.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs uppercase hover:bg-amber-400 transition">
                                        {{ __('app.view_order') }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-12 text-gray-500 text-xs">No orders found.</p>
                        @endforelse
                    </div>

                    <div class="pt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
