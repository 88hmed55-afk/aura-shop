@extends('layouts.app')

@section('content')
<div class="py-16 bg-gray-50 dark:bg-aura-dark min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="glass-panel p-8 sm:p-12 rounded-3xl border border-gray-200 dark:border-gray-800 text-center space-y-6 shadow-2xl relative overflow-hidden">
            <div class="w-20 h-20 rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 flex items-center justify-center text-4xl mx-auto animate-bounce">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div>
                <span class="text-xs font-bold text-amber-400 tracking-widest uppercase">{{ __('app.order_number') }}: {{ $order->order_number }}</span>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ __('app.order_success_title') }}</h1>
                <p class="text-sm text-gray-400 mt-3 leading-relaxed max-w-lg mx-auto">{{ __('app.order_success_msg') }}</p>
            </div>

            <!-- Summary Box -->
            <div class="p-4 rounded-2xl bg-gray-900/60 border border-gray-800 text-left text-xs space-y-2 font-mono">
                <div class="flex justify-between text-gray-400">
                    <span>Customer:</span>
                    <span class="text-white font-bold">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Payment Status:</span>
                    <span class="text-emerald-400 font-bold uppercase">{{ $order->payment_status }}</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Total Amount Paid:</span>
                    <span class="text-amber-400 font-bold text-sm">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                <a href="{{ route('invoice.show', $order->order_number) }}" target="_blank"
                   class="px-6 py-3.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs uppercase hover:bg-amber-400 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-print"></i> {{ __('app.download_invoice') }}
                </a>
                <a href="{{ route('home') }}"
                   class="px-6 py-3.5 rounded-xl glass-panel text-white font-bold text-xs uppercase hover:bg-white/10 transition border border-gray-700">
                    {{ __('app.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
