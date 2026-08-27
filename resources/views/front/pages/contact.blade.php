@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-50 dark:bg-aura-dark min-h-screen">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto text-center mb-16 space-y-4">
            <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/10 text-amber-400 border border-amber-500/20">
                VIP Concierge
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tight uppercase">
                Contact <span class="text-gradient-gold">Private Client Advisory</span>
            </h1>
            <p class="text-xs md:text-sm text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl mx-auto">
                Have inquiries about a rare timepiece, bespoke sizing, or private international delivery? Our advisors are at your immediate disposal.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <!-- Contact Info Sidebar -->
            <div class="lg:col-span-1 space-y-4">
                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <h3 class="font-bold text-sm text-white">Global Headquarters</h3>
                    <p class="text-xs text-gray-400">Level 48, Burj Daman Tower, DIFC, Dubai, UAE</p>
                </div>

                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <h3 class="font-bold text-sm text-white">Private Line</h3>
                    <p class="text-xs text-gray-400 font-mono">+971 4 800 AURA (2872)</p>
                    <p class="text-[10px] text-emerald-400 font-bold uppercase">24/7 VIP Assistance</p>
                </div>

                <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-base">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <h3 class="font-bold text-sm text-white">Electronic Dispatch</h3>
                    <p class="text-xs text-gray-400 font-mono">concierge@aura-luxury.com</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="glass-panel p-8 sm:p-10 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                            <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.full_name') }}</label>
                                <input type="text" name="name" required value="{{ auth()->user()->name ?? old('name') }}" placeholder="Your Name"
                                       class="w-full px-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.email_address') }}</label>
                                <input type="email" name="email" required value="{{ auth()->user()->email ?? old('email') }}" placeholder="name@domain.com"
                                       class="w-full px-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Subject</label>
                            <input type="text" name="subject" required placeholder="Inquiry regarding bespoke commission or order"
                                   class="w-full px-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Message</label>
                            <textarea name="message" rows="5" required placeholder="Please describe how our advisors may assist you..."
                                      class="w-full px-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition"></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Send Concierge Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
