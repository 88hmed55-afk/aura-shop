@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.customers') }} Directory</h1>
            <p class="text-xs text-gray-400 mt-1">Directory of registered VIP clientele and high-net-worth patrons.</p>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-aura-card p-4 rounded-3xl border border-aura-border">
        <form action="{{ route('admin.customers.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-10 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Client Name, Email, Phone..."
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

    <!-- Customers Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($customers as $customer)
            <div class="bg-aura-card p-6 rounded-3xl border border-aura-border flex flex-col justify-between group hover:border-amber-500/40 transition">
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $customer->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=120&auto=format&fit=crop&q=80' }}" class="w-14 h-14 rounded-2xl object-cover border border-aura-border">
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-white truncate">{{ $customer->name }}</h3>
                            <p class="text-[10px] text-gray-400 font-mono truncate">{{ $customer->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                VIP Patron
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2 border-t border-aura-border/60 text-center">
                        <div class="p-2 rounded-xl bg-aura-panel">
                            <span class="block text-[9px] uppercase font-bold text-gray-500">Orders Placed</span>
                            <span class="font-mono font-bold text-white text-xs">{{ $customer->orders_count }}</span>
                        </div>
                        <div class="p-2 rounded-xl bg-aura-panel">
                            <span class="block text-[9px] uppercase font-bold text-gray-500">Joined Date</span>
                            <span class="font-mono text-gray-400 text-[10px]">{{ $customer->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-aura-border flex items-center justify-between">
                    <span class="text-[10px] text-gray-500"><i class="fa-solid fa-phone me-1"></i> {{ $customer->phone ?: 'No phone' }}</span>
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="px-3 py-1.5 rounded-xl bg-aura-panel text-amber-400 hover:bg-amber-500 hover:text-slate-950 font-bold text-xs transition flex items-center gap-1">
                        Profile <i class="fa-solid fa-chevron-right text-[9px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-12 text-center text-gray-500">
                <i class="fa-solid fa-users text-3xl mb-2 block"></i>
                No clients found.
            </div>
        @endforelse
    </div>

    @if($customers->hasPages())
        <div class="p-4 bg-aura-card rounded-3xl border border-aura-border">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
