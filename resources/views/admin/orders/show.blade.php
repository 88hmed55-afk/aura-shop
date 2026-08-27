@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-amber-500 font-bold uppercase hover:underline flex items-center gap-2 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
            <h1 class="text-2xl font-black text-white uppercase tracking-tight">Order #{{ $order->order_number }}</h1>
            <p class="text-xs text-gray-400">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('invoice.show', $order->order_number) }}" target="_blank" class="px-4 py-2.5 rounded-2xl bg-aura-card border border-aura-border text-amber-400 font-bold text-xs hover:border-amber-500 transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print Official Invoice
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Items & Order Timeline -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
                <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                    Ordered Creations ({{ $order->items->count() }})
                </h3>

                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between gap-4 py-2 border-b border-aura-border/50 last:border-0">
                            <div class="flex items-center gap-4">
                                <img src="{{ $item->image }}" class="w-14 h-14 rounded-2xl object-cover border border-aura-border shrink-0">
                                <div>
                                    <h4 class="font-bold text-xs text-white">{{ $item->product_name }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Qty: {{ $item->quantity }} &times; ${{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="font-black text-amber-500 text-sm">${{ number_format($item->total, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Financial Calculation Table -->
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border">
                <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4 mb-4">
                    Financial Ledger
                </h3>

                <div class="space-y-2.5 text-xs text-gray-400">
                    <div class="flex justify-between">
                        <span>Items Subtotal</span>
                        <span class="text-white font-mono">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-400">
                            <span>Coupon Discount ({{ $order->coupon_code }})</span>
                            <span class="font-mono">-${{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span>Armored Courier Shipping</span>
                        <span class="text-white font-mono">${{ number_format($order->shipping_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>VAT & Luxury Surcharge (15%)</span>
                        <span class="text-white font-mono">${{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-black text-white pt-4 border-t border-aura-border">
                        <span>Total Paid / Payable</span>
                        <span class="text-amber-500 font-mono">${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Status Management & Client Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Update Status Box -->
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-amber-500/30 space-y-6">
                <h3 class="text-sm font-black text-amber-400 uppercase tracking-wider border-b border-aura-border pb-4">
                    Fulfillment Dispatch
                </h3>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5">Fulfillment Status</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs focus:outline-none focus:border-amber-500">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5">Payment Status</label>
                        <select name="payment_status" class="w-full px-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs focus:outline-none focus:border-amber-500">
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1.5">Tracking Number</label>
                        <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="e.g. AURA-TRK-987654"
                               class="w-full px-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs font-mono focus:outline-none focus:border-amber-500">
                    </div>

                    <button type="submit" class="w-full py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                        Update Fulfillment
                    </button>
                </form>
            </div>

            <!-- Client & Shipping Details -->
            <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-4">
                <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                    Client & Destination
                </h3>

                <div class="space-y-3 text-xs text-gray-400">
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-gray-500">Client Name</span>
                        <p class="font-bold text-white mt-0.5">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-gray-500">Email Address</span>
                        <p class="font-mono text-gray-300 mt-0.5">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold uppercase text-gray-500">Direct Phone</span>
                        <p class="font-mono text-gray-300 mt-0.5">{{ $order->customer_phone }}</p>
                    </div>

                    @php $addr = $order->shipping_address; @endphp
                    <div class="pt-2 border-t border-aura-border">
                        <span class="block text-[10px] font-bold uppercase text-gray-500 mb-1">Delivery Address</span>
                        <p class="text-gray-300 leading-relaxed">
                            {{ $addr['address_line_1'] ?? '' }}<br>
                            @if(!empty($addr['address_line_2'])) {{ $addr['address_line_2'] }}<br> @endif
                            {{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} {{ $addr['postal_code'] ?? '' }}<br>
                            <strong class="text-amber-400">{{ $addr['country'] ?? '' }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
