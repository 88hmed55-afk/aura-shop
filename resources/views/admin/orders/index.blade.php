@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.orders') }} Management</h1>
            <p class="text-xs text-gray-400 mt-1">Track high-value purchases, dispatch courier tracking numbers, and manage statuses.</p>
        </div>
    </div>

    <!-- Filters & Status Pills -->
    <div class="bg-aura-card p-4 rounded-3xl border border-aura-border space-y-4">
        <!-- Status Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ !request('status') ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'bg-aura-panel text-gray-400 hover:text-white' }}">
                All Orders
            </a>
            @foreach(['pending' => 'Pending', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $stKey => $stLabel)
                <a href="{{ route('admin.orders.index', ['status' => $stKey]) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap {{ request('status') === $stKey ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' : 'bg-aura-panel text-gray-400 hover:text-white' }}">
                    {{ $stLabel }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="sm:col-span-10 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order #, Customer Name, Email..."
                       class="w-full ps-10 pe-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                <i class="fa-solid fa-magnifying-glass absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 text-xs pointer-events-none"></i>
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2.5 rounded-2xl bg-gray-800 hover:bg-gray-700 text-white font-bold text-xs uppercase tracking-wider transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-aura-card rounded-3xl border border-aura-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-aura-panel/50 border-b border-aura-border text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-4 px-6 text-start">Order Number</th>
                        <th class="py-4 px-6 text-start">Client</th>
                        <th class="py-4 px-6 text-start">Items</th>
                        <th class="py-4 px-6 text-start">Total Amount</th>
                        <th class="py-4 px-6 text-start">Fulfillment Status</th>
                        <th class="py-4 px-6 text-start">Payment</th>
                        <th class="py-4 px-6 text-start">Date</th>
                        <th class="py-4 px-6 text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aura-border">
                    @forelse($orders as $order)
                        <tr class="hover:bg-aura-panel/30 transition">
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono font-bold text-amber-400 hover:underline">
                                    #{{ $order->order_number }}
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-white">{{ $order->customer_name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $order->customer_email }}</div>
                            </td>
                            <td class="py-4 px-6 text-gray-300">
                                <span class="font-bold text-white">{{ $order->items->count() }}</span> items
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-black text-amber-500">${{ number_format($order->total, 2) }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-{{ $order->status_badge_color }}-500/20 text-{{ $order->status_badge_color }}-400 border border-{{ $order->status_badge_color }}-500/30">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($order->payment_status === 'paid')
                                    <span class="inline-flex items-center gap-1.5 text-emerald-400 font-bold text-[10px]">
                                        <i class="fa-solid fa-circle-check"></i> Paid
                                    </span>
                                @elseif($order->payment_status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 text-amber-400 font-bold text-[10px]">
                                        <i class="fa-solid fa-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-rose-400 font-bold text-[10px]">
                                        <i class="fa-solid fa-circle-xmark"></i> {{ $order->payment_status }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-400 text-[10px]">
                                {{ $order->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-6 text-end">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-aura-panel text-amber-400 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs transition">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-500">
                                <i class="fa-solid fa-receipt text-3xl mb-2 block"></i>
                                No orders matching the current filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-aura-border">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
