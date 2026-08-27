@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Top Executive Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">Executive Intelligence</h1>
            <p class="text-xs text-gray-400 mt-1">Real-time revenue telemetry, luxury order fulfillment, and client activity.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.create') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Add Luxury Creation
            </a>
        </div>
    </div>

    <!-- 4 High-Impact KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Revenue -->
        <div class="bg-aura-card p-6 rounded-3xl border border-aura-border relative overflow-hidden group hover:border-amber-500/50 transition">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Total Net Revenue</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-base border border-amber-500/20">
                    <i class="fa-solid fa-vault"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white">${{ number_format($totalSales, 2) }}</div>
            <div class="mt-3 flex items-center gap-2 text-[10px] text-emerald-400 font-bold">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>+18.4% vs last month</span>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-aura-card p-6 rounded-3xl border border-aura-border relative overflow-hidden group hover:border-amber-500/50 transition">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Total Orders</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-400 flex items-center justify-center text-base border border-sky-500/20">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white">{{ number_format($totalOrders) }}</div>
            <div class="mt-3 flex items-center gap-2 text-[10px] text-sky-400 font-bold">
                <i class="fa-solid fa-cubes"></i>
                <span>Active shipments</span>
            </div>
        </div>

        <!-- VIP Customers -->
        <div class="bg-aura-card p-6 rounded-3xl border border-aura-border relative overflow-hidden group hover:border-amber-500/50 transition">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Registered Clients</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center text-base border border-purple-500/20">
                    <i class="fa-solid fa-crown"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-white">{{ number_format($totalCustomers) }}</div>
            <div class="mt-3 flex items-center gap-2 text-[10px] text-purple-400 font-bold">
                <i class="fa-solid fa-shield-halved"></i>
                <span>Verified high-tier accounts</span>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="bg-aura-card p-6 rounded-3xl border border-aura-border relative overflow-hidden group hover:border-rose-500/50 transition">
            <div class="flex justify-between items-start mb-4">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Low Stock Vault</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-400 flex items-center justify-center text-base border border-rose-500/20">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
            <div class="text-2xl font-black {{ $lowStockCount > 0 ? 'text-rose-400' : 'text-white' }}">{{ $lowStockCount }} items</div>
            <div class="mt-3 flex items-center gap-2 text-[10px] {{ $lowStockCount > 0 ? 'text-rose-400 font-bold' : 'text-gray-400' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>{{ $lowStockCount > 0 ? 'Urgent re-stock required' : 'Inventory healthy' }}</span>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Monthly Revenue Chart -->
        <div class="lg:col-span-2 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wider">Revenue Dynamics</h3>
                    <p class="text-xs text-gray-400">Monthly sales volume and revenue generation</p>
                </div>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">Live Sync</span>
            </div>
            <div class="h-72 w-full pt-4">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Category Distribution Doughnut Chart -->
        <div class="lg:col-span-1 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wider">Category Ratio</h3>
                    <p class="text-xs text-gray-400">Inventory volume by atelier</p>
                </div>
            </div>
            <div class="h-64 w-full flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Selling Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders Table -->
        <div class="lg:col-span-2 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-white uppercase tracking-wider">Latest VIP Orders</h3>
                    <p class="text-xs text-gray-400">Most recent customer acquisitions</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-amber-500 hover:underline">
                    View All Orders <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-start text-xs">
                    <thead>
                        <tr class="border-b border-aura-border text-gray-500 uppercase text-[10px] tracking-wider">
                            <th class="pb-3 text-start">Order Number</th>
                            <th class="pb-3 text-start">Customer</th>
                            <th class="pb-3 text-start">Total</th>
                            <th class="pb-3 text-start">Status</th>
                            <th class="pb-3 text-start">Date</th>
                            <th class="pb-3 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aura-border">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-aura-panel/50 transition">
                                <td class="py-3 font-mono font-bold text-amber-400">#{{ $order->order_number }}</td>
                                <td class="py-3">
                                    <div class="font-bold text-white">{{ $order->customer_name }}</div>
                                    <div class="text-[10px] text-gray-500">{{ $order->customer_email }}</div>
                                </td>
                                <td class="py-3 font-bold text-white">${{ number_format($order->total, 2) }}</td>
                                <td class="py-3">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-{{ $order->status_badge_color }}-500/20 text-{{ $order->status_badge_color }}-400 border border-{{ $order->status_badge_color }}-500/30">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-gray-400 text-[10px]">{{ $order->created_at->diffForHumans() }}</td>
                                <td class="py-3 text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 rounded-xl bg-aura-panel text-gray-400 hover:text-amber-400 transition">
                                        <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-6 text-center text-gray-500">No orders placed yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performing Products -->
        <div class="lg:col-span-1 bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <div>
                <h3 class="text-base font-black text-white uppercase tracking-wider">Top Rated Creations</h3>
                <p class="text-xs text-gray-400">Client favorite masterworks</p>
            </div>

            <div class="space-y-4">
                @forelse($topProducts as $prod)
                    <div class="flex items-center gap-4">
                        <img src="{{ $prod->image_url }}" class="w-12 h-12 rounded-xl object-cover border border-aura-border shrink-0">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-xs text-white truncate">{{ $prod->name }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-amber-500 font-bold text-xs">${{ number_format($prod->price, 2) }}</span>
                                <span class="text-[10px] text-gray-500">★ {{ number_format($prod->rating, 1) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $prod->id) }}" class="p-2 rounded-xl bg-aura-panel text-gray-400 hover:text-white text-xs">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-gray-500">No products available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Monthly Revenue Line/Bar Chart
        const monthlyData = @json($monthlySales);
        const revCtx = document.getElementById('revenueChart').getContext('2d');
        
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.month || 'Current'),
                datasets: [{
                    label: 'Revenue ($)',
                    data: monthlyData.map(d => d.revenue || 0),
                    borderColor: '#D4AF37',
                    backgroundColor: 'rgba(212, 175, 55, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#D4AF37',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: '#1F2637' },
                        ticks: { color: '#94A3B8', font: { size: 10 } }
                    },
                    y: {
                        grid: { color: '#1F2637' },
                        ticks: { color: '#94A3B8', font: { size: 10 } }
                    }
                }
            }
        });

        // Category Doughnut Chart
        const catData = @json($categoryStats);
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catData.map(c => c.name_en),
                datasets: [{
                    data: catData.map(c => c.products_count),
                    backgroundColor: [
                        '#D4AF37',
                        '#38BDF8',
                        '#A855F7',
                        '#10B981',
                        '#F43F5E'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#94A3B8', font: { size: 10 }, boxWidth: 10 }
                    }
                }
            }
        });
    });
</script>
@endpush
