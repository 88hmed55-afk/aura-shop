@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Hero Section -->
        <div class="max-w-4xl mx-auto text-center mb-16 space-y-4">
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Heritage & Prestige
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-gray-900 dark:text-white tracking-tight uppercase">
                Redefining the Universe of <span class="text-gradient-gold">Luxury Commerce</span>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-gray-400 leading-relaxed max-w-2xl mx-auto">
                AURA was founded on a singular vision: to curate timeless perfection and engineer a digital boutique experience unmatched in elegance, precision, and performance.
            </p>
        </div>

        <!-- Vision / Mission / Values Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <div class="glass-panel p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Uncompromising Quality</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Every timepiece, fragrance, leather creation, and couture jewel in our catalog is authenticated by master connoisseurs and guaranteed 100% genuine.
                </p>
            </div>

            <div class="glass-panel p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Bespoke Concierge</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Our dedicated 24/7 private client service ensures personal advisory, VIP white-glove packaging, and discreet express delivery to your penthouse or yacht.
                </p>
            </div>

            <div class="glass-panel p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-lg font-bold text-white uppercase tracking-wider">Global Exclusivity</h3>
                <p class="text-xs text-gray-400 leading-relaxed">
                    We collaborate with rare artisan ateliers in Geneva, Milan, Paris, and Tokyo to bring limited editions that cannot be acquired in ordinary luxury retail.
                </p>
            </div>
        </div>

        <!-- Metrics / Counters -->
        <div class="glass-panel p-10 rounded-3xl border border-gray-200 dark:border-gray-800 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-black text-amber-500 mb-1">50K+</div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">VIP Clients Worldwide</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black text-amber-500 mb-1">100%</div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Certified Authenticity</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black text-amber-500 mb-1">45+</div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Artisan Houses</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-black text-amber-500 mb-1">24h</div>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Express Dispatch</div>
            </div>
        </div>
    </div>
</div>
@endsection
