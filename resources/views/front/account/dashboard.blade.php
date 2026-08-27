@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Account Sidebar Menu (3 cols) -->
            <div class="lg:col-span-3 space-y-4">
                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 text-center space-y-3">
                    <img src="{{ auth()->user()->getAvatarUrl() }}" class="w-20 h-20 rounded-full mx-auto border-2 border-amber-500 shadow-lg object-cover">
                    <div>
                        <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-gray-400 font-mono mt-0.5">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                <div class="glass-panel p-4 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-1 text-xs font-semibold">
                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-amber-500 text-slate-950 font-bold">
                        <i class="fa-solid fa-chart-pie"></i> {{ __('app.dashboard') }}
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                        <i class="fa-solid fa-box-open"></i> {{ __('app.my_orders') }}
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-400 hover:text-white hover:bg-gray-800 transition">
                        <i class="fa-solid fa-location-dot"></i> {{ __('app.my_addresses') }}
                    </a>
                </div>
            </div>

            <!-- Dashboard Main View (9 cols) -->
            <div class="lg:col-span-9 space-y-8">
                <!-- Metrics -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-bold">{{ __('app.total_orders') }}</span>
                            <div class="text-2xl font-black text-white mt-1">{{ $totalOrdersCount }}</div>
                        </div>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-vault"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-bold">Lifetime Spent</span>
                            <div class="text-2xl font-black text-emerald-400 mt-1">${{ number_format($totalSpent, 2) }}</div>
                        </div>
                    </div>

                    <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center text-xl shrink-0">
                            <i class="fa-solid fa-heart"></i>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-bold">Saved Wishlist</span>
                            <div class="text-2xl font-black text-white mt-1">{{ auth()->user()->wishlists()->count() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-4">
                        <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ __('app.recent_orders') }}</h3>
                        <a href="{{ route('account.orders') }}" class="text-xs font-bold text-amber-500 hover:underline">View All</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-gray-400 uppercase font-bold border-b border-gray-800">
                                    <th class="py-3 px-4">{{ __('app.order_number') }}</th>
                                    <th class="py-3 px-4">{{ __('app.date') }}</th>
                                    <th class="py-3 px-4">{{ __('app.order_status') }}</th>
                                    <th class="py-3 px-4">{{ __('app.total') }}</th>
                                    <th class="py-3 px-4 text-right">{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="py-4 px-4 font-mono font-bold text-amber-400">{{ $order->order_number }}</td>
                                        <td class="py-4 px-4 text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 px-4">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase bg-{{ $order->status_badge_color }}-500/20 text-{{ $order->status_badge_color }}-400 border border-{{ $order->status_badge_color }}-500/30">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 font-bold text-white">${{ number_format($order->total, 2) }}</td>
                                        <td class="py-4 px-4 text-right">
                                            <a href="{{ route('account.order.detail', $order->order_number) }}" class="px-3 py-1.5 rounded-lg bg-gray-800 text-amber-400 hover:bg-amber-500 hover:text-slate-950 font-bold transition">
                                                {{ __('app.view_order') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">No orders placed yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
