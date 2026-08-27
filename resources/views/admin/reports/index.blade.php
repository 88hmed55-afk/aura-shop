@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.analytics') }} & Financial Intelligence</h1>
            <p class="text-xs text-gray-400 mt-1">Audit monthly luxury revenues, transaction frequency, and top revenue drivers.</p>
        </div>
    </div>

    <!-- Monthly Revenue Table & Performance Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                Monthly Performance Ledger
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs">
                    <thead class="bg-aura-panel/50 border-b border-aura-border text-gray-400 uppercase text-[10px] tracking-wider">
                        <tr>
                            <th class="py-4 px-6 text-start">Period (Month)</th>
                            <th class="py-4 px-6 text-start">Completed Orders</th>
                            <th class="py-4 px-6 text-start">Gross Volume</th>
                            <th class="py-4 px-6 text-end">Average Order Value (AOV)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aura-border">
                        @forelse($monthlyRevenue as $rev)
                            @php
                                $aov = $rev->total_orders > 0 ? ($rev->revenue / $rev->total_orders) : 0;
                            @endphp
                            <tr class="hover:bg-aura-panel/30 transition">
                                <td class="py-4 px-6 font-mono font-bold text-white">
                                    {{ $rev->month ?: 'Current Period' }}
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-300">
                                    {{ $rev->total_orders }}
                                </td>
                                <td class="py-4 px-6 font-black text-amber-400">
                                    ${{ number_format($rev->revenue, 2) }}
                                </td>
                                <td class="py-4 px-6 font-mono text-emerald-400 text-end">
                                    ${{ number_format($aov, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500">No revenue records registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Catalog -->
        <div class="lg:col-span-1 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                Highest Trailing Creations
            </h3>

            <div class="space-y-4">
                @forelse($topSellingProducts as $item)
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->image_url }}" class="w-12 h-12 rounded-xl object-cover border border-aura-border shrink-0">
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-white truncate">{{ $item->name_en }}</h4>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-amber-500 font-bold text-xs">${{ number_format($item->price, 2) }}</span>
                                <span class="text-[10px] text-gray-400">{{ $item->reviews_count }} reviews</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-500">No data available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
