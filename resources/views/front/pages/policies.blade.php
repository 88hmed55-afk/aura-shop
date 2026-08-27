@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8 max-w-4xl">
        <div class="text-center mb-16 space-y-4">
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20">
                Governance & Terms
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight uppercase">
                Policies & <span class="text-gradient-gold">Client Guarantee</span>
            </h1>
            <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl mx-auto">
                Review our comprehensive terms regarding privacy, warranty, and insured courier delivery.
            </p>
        </div>

        <div class="glass-panel p-8 sm:p-12 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-8 text-xs text-gray-400 leading-relaxed">
            <section class="space-y-3">
                <h3 class="text-base font-bold text-white uppercase tracking-wider text-amber-400">1. Authenticity & Warranty Guarantee</h3>
                <p>
                    AURA stands firmly behind every creation in our boutique. All items are rigorously checked by certified specialists. Each purchase includes the manufacturer's global international warranty covering technical defects, movement repairs, and structural integrity for up to 5 years.
                </p>
            </section>

            <section class="space-y-3">
                <h3 class="text-base font-bold text-white uppercase tracking-wider text-amber-400">2. Privacy & Data Discretion</h3>
                <p>
                    We protect client privacy with banking-grade 256-bit encryption. We never sell, lease, or distribute private customer information. Payment transactions are processed directly via secure Tier-1 PCI-DSS compliant gateways.
                </p>
            </section>

            <section class="space-y-3">
                <h3 class="text-base font-bold text-white uppercase tracking-wider text-amber-400">3. White-Glove Shipping & Customs Clearance</h3>
                <p>
                    All high-value orders are shipped with comprehensive tamper-evident packaging and armored transit. Import duties and VAT are calculated upfront at checkout so there are never unexpected charges upon arrival.
                </p>
            </section>

            <section class="space-y-3">
                <h3 class="text-base font-bold text-white uppercase tracking-wider text-amber-400">4. Returns & Inspection Period</h3>
                <p>
                    You have 14 days from physical delivery to request an exchange or refund. Items must be returned in unworn condition, in original presentation packaging with serial tags attached. Custom-engraved or bespoke orders cannot be returned.
                </p>
            </section>
        </div>
    </div>
</div>
@endsection
