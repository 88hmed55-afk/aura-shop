@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        <div class="text-center mb-16 space-y-4">
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Help & Assistance
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight uppercase">
                Frequently Asked <span class="text-gradient-gold">Questions</span>
            </h1>
            <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl mx-auto">
                Find clear answers regarding authenticity certificates, global insured shipping, concierge returns, and bespoke orders.
            </p>
        </div>

        <div class="space-y-4" x-data="{ active: null }">
            <!-- FAQ 1 -->
            <div class="glass-panel rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden transition">
                <button @click="active = (active === 1 ? null : 1)" class="w-full p-6 text-start flex items-center justify-between gap-4">
                    <span class="font-bold text-sm text-gray-900 dark:text-white">How do I verify the authenticity of an AURA timepiece or jewel?</span>
                    <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300" :class="active === 1 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 1" x-collapse class="px-6 pb-6 text-xs text-gray-400 leading-relaxed border-t border-gray-800 pt-4">
                    Each item in our catalog arrives in its original manufacturer presentation vault, accompanied by a stamped international warranty card, individual serial number verification, and an NFC-enabled digital certificate of authenticity backed by AURA's master horologists.
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="glass-panel rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden transition">
                <button @click="active = (active === 2 ? null : 2)" class="w-full p-6 text-start flex items-center justify-between gap-4">
                    <span class="font-bold text-sm text-gray-900 dark:text-white">What are your international shipping times and insurance policies?</span>
                    <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300" :class="active === 2 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 2" x-collapse class="px-6 pb-6 text-xs text-gray-400 leading-relaxed border-t border-gray-800 pt-4">
                    All orders are dispatched via armored VIP courier (e.g. DHL Express / Ferrari Group) with 100% full-value insurance coverage. Gulf deliveries take 24–48 hours; international deliveries arrive within 2–4 business days.
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="glass-panel rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden transition">
                <button @click="active = (active === 3 ? null : 3)" class="w-full p-6 text-start flex items-center justify-between gap-4">
                    <span class="font-bold text-sm text-gray-900 dark:text-white">What payment methods are supported?</span>
                    <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300" :class="active === 3 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 3" x-collapse class="px-6 pb-6 text-xs text-gray-400 leading-relaxed border-t border-gray-800 pt-4">
                    We accept Visa, Mastercard, American Express, Apple Pay, Tabby/Tamara split payments, and Cash on Delivery for regional select destinations. High-value wire transfers can also be arranged through our VIP concierge.
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="glass-panel rounded-3xl border border-gray-200 dark:border-gray-800 overflow-hidden transition">
                <button @click="active = (active === 4 ? null : 4)" class="w-full p-6 text-start flex items-center justify-between gap-4">
                    <span class="font-bold text-sm text-gray-900 dark:text-white">What is the return and exchange policy?</span>
                    <i class="fa-solid fa-chevron-down text-amber-500 transition-transform duration-300" :class="active === 4 ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="active === 4" x-collapse class="px-6 pb-6 text-xs text-gray-400 leading-relaxed border-t border-gray-800 pt-4">
                    We offer a complimentary 14-day return privilege on unworn items in pristine condition with all tamper seals and original documentation intact. Our courier will collect the package directly from your address at no cost.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
