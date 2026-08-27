@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.customers.index') }}" class="text-xs text-amber-500 font-bold uppercase hover:underline flex items-center gap-2 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Customers
            </a>
            <h1 class="text-2xl font-black text-white uppercase tracking-tight">Client Dossier: {{ $customer->name }}</h1>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Customer Profile Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border text-center space-y-4">
                <img src="{{ $customer->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=160&auto=format&fit=crop&q=80' }}" class="w-24 h-24 rounded-3xl object-cover border-2 border-amber-500/50 mx-auto shadow-xl">
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $customer->name }}</h2>
                    <p class="text-xs text-gray-400 font-mono">{{ $customer->email }}</p>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-[9px] font-black uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                        VIP Tier 1 Account
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-4 border-t border-aura-border text-start text-xs">
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Phone</span>
                        <span class="text-gray-300">{{ $customer->phone ?: 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Locale</span>
                        <span class="text-amber-400 uppercase font-bold">{{ $customer->locale ?? 'en' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Member Since</span>
                        <span class="text-gray-300">{{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold block">Total Spent</span>
                        <span class="text-amber-500 font-bold font-mono">${{ number_format($customer->orders->where('payment_status', 'paid')->sum('total'), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Saved Addresses -->
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-4">
                <h3 class="text-xs font-black text-white uppercase tracking-wider border-b border-aura-border pb-3">
                    Registered Delivery Destinations ({{ $customer->addresses->count() }})
                </h3>
                <div class="space-y-3">
                    @forelse($customer->addresses as $address)
                        <div class="p-3 rounded-2xl bg-aura-panel border border-aura-border text-xs text-gray-400 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-white">{{ $address->title }}</span>
                                @if($address->is_default)
                                    <span class="text-[8px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded font-black uppercase">Default</span>
                                @endif
                            </div>
                            <p>{{ $address->address_line_1 }}</p>
                            <p>{{ $address->city }}, {{ $address->country }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No saved addresses on file.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Orders History -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
                <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                    Transaction & Order History ({{ $customer->orders->count() }})
                </h3>

                <div class="space-y-4">
                    @forelse($customer->orders as $order)
                        <div class="p-4 rounded-2xl bg-aura-panel border border-aura-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3">
                                    <span class="font-mono font-bold text-amber-400 text-xs">#{{ $order->order_number }}</span>
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-{{ $order->status_badge_color }}-500/20 text-{{ $order->status_badge_color }}-400 border border-{{ $order->status_badge_color }}-500/30">
                                        {{ $order->status }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1">{{ $order->created_at->format('M d, Y h:i A') }} • {{ $order->items->count() }} items</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="text-end">
                                    <span class="text-xs font-black text-white block">${{ number_format($order->total, 2) }}</span>
                                    <span class="text-[9px] text-emerald-400 font-bold capitalize">{{ $order->payment_status }}</span>
                                </div>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 rounded-xl bg-aura-card text-gray-400 hover:text-amber-400 transition">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 py-6 text-center">This customer has not placed any orders yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
