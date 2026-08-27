@extends('layouts.app')

@section('content')
<div class="min-h-screen py-16 flex items-center justify-center bg-gray-50 dark:bg-aura-dark relative overflow-hidden">
    <!-- Ambient Glow -->
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-md mx-auto glass-panel p-8 sm:p-10 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 mb-4 border border-amber-500/20">
                    <i class="fa-solid fa-gem text-lg"></i>
                </div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase">{{ __('app.register') }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Join the premier universe of curated luxury goods.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.full_name') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.email_address') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.phone_number') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+971 50 123 4567"
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.password') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" name="password" required
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.confirm_password') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <input type="password" name="password_confirmation" required
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                        {{ __('app.register') }}
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-800 text-center text-xs text-gray-400">
                Already an AURA VIP member?
                <a href="{{ route('login') }}" class="text-amber-500 font-bold hover:underline ms-1">{{ __('app.login') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
