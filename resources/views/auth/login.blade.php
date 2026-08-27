@extends('layouts.app')

@section('content')
<div class="min-h-screen py-16 flex items-center justify-center bg-gray-50 dark:bg-aura-dark relative overflow-hidden">
    <!-- Ambient Background Glows -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-md mx-auto glass-panel p-8 sm:p-10 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
            <!-- Header / Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 mb-4 border border-amber-500/20">
                    <i class="fa-solid fa-lock text-lg"></i>
                </div>
                <h1 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight uppercase">{{ __('app.login') }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Access your private luxury vault and orders.</p>
            </div>

            <!-- Demo Credentials Helper Card -->
            <div class="mb-6 p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-400">
                <div class="font-bold flex items-center gap-1 mb-1">
                    <i class="fa-solid fa-circle-info"></i> Demo Credentials
                </div>
                <div class="text-[11px] text-gray-300 space-y-1">
                    <p><span class="text-amber-400 font-bold">Admin:</span> admin@aura.com / password</p>
                    <p><span class="text-amber-400 font-bold">Customer:</span> customer@aura.com / password</p>
                </div>
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

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">{{ __('app.email_address') }}</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email', 'admin@aura.com') }}" required autofocus
                               class="w-full ps-10 pe-4 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('app.password') }}</label>
                        <a href="#" class="text-[10px] text-amber-500 hover:underline">Forgot password?</a>
                    </div>
                    <div class="relative" x-data="{ showPass: false }">
                        <span class="absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 pointer-events-none text-xs">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" name="password" value="password" required
                               class="w-full ps-10 pe-10 py-3 rounded-2xl bg-gray-900/80 border border-gray-800 text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 end-0 flex items-center pe-4 text-gray-500 hover:text-white text-xs">
                            <i :class="showPass ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-gray-800 border-gray-700 text-amber-500 focus:ring-amber-500">
                        <span class="text-xs text-gray-400">{{ __('app.remember_me') }}</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                    {{ __('app.login') }}
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-800 text-center text-xs text-gray-400">
                Don't have an AURA account?
                <a href="{{ route('register') }}" class="text-amber-500 font-bold hover:underline ms-1">{{ __('app.register') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
