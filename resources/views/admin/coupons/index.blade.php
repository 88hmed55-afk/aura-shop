@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.coupons') }} & VIP Passes</h1>
            <p class="text-xs text-gray-400 mt-1">Manage promotional concession codes, percentage discounts, and fixed credits.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Create Coupon
        </button>
    </div>

    <!-- Coupons Table -->
    <div class="bg-aura-card rounded-3xl border border-aura-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-aura-panel/50 border-b border-aura-border text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-4 px-6 text-start">Coupon Code</th>
                        <th class="py-4 px-6 text-start">Discount Type</th>
                        <th class="py-4 px-6 text-start">Discount Value</th>
                        <th class="py-4 px-6 text-start">Min. Spend</th>
                        <th class="py-4 px-6 text-start">Usage Limit</th>
                        <th class="py-4 px-6 text-start">Status</th>
                        <th class="py-4 px-6 text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aura-border">
                    @forelse($coupons as $coupon)
                        <tr class="hover:bg-aura-panel/30 transition">
                            <td class="py-4 px-6">
                                <span class="px-3 py-1.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 font-mono font-black text-xs uppercase tracking-widest">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-300 capitalize">
                                {{ $coupon->type }}
                            </td>
                            <td class="py-4 px-6 font-bold text-white">
                                @if($coupon->type === 'percentage')
                                    {{ $coupon->value }}% OFF
                                @else
                                    ${{ number_format($coupon->value, 2) }} OFF
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-400">
                                ${{ number_format($coupon->min_order_amount, 2) }}
                            </td>
                            <td class="py-4 px-6 text-gray-400">
                                {{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($coupon->is_active && (!$coupon->expires_at || $coupon->expires_at->isFuture()))
                                    <span class="inline-flex items-center gap-1.5 text-emerald-400 font-bold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-rose-400 font-bold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span> Expired
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-end">
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Revoke this coupon?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">
                                No promotional coupons configured.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Coupon Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="createModal = false"></div>
        <div class="relative bg-aura-card border border-aura-border rounded-3xl p-6 sm:p-8 max-w-md w-full z-10 shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-aura-border pb-4">
                <h3 class="text-base font-bold text-white">Create Promotional Code</h3>
                <button @click="createModal = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Coupon Code *</label>
                    <input type="text" name="code" required placeholder="e.g. VIP2026" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white uppercase font-mono focus:border-amber-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Discount Type *</label>
                        <select name="type" required class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount ($)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Value *</label>
                        <input type="number" step="0.01" name="value" required placeholder="15" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Min. Order Amount ($)</label>
                        <input type="number" step="0.01" name="min_order_amount" placeholder="0" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Usage Limit (Times)</label>
                        <input type="number" name="usage_limit" placeholder="100" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Expiration Date (Optional)</label>
                    <input type="date" name="expires_at" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-aura-border">
                    <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-xl bg-aura-panel text-gray-400 text-xs font-bold hover:bg-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">Save Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
