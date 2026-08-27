<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AURA Administration — Luxury E-Commerce Architecture</title>

    <!-- Google Fonts: Plus Jakarta Sans & Cairo for Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Pro -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        aura: {
                            gold: '#D4AF37',
                            'gold-light': '#F3E5AB',
                            'gold-dark': '#AA7C11',
                            dark: '#0B0D13',
                            panel: '#121620',
                            card: '#161B26',
                            border: '#1F2637'
                        }
                    },
                    fontFamily: {
                        sans: ['{{ app()->getLocale() === "ar" ? "Cairo" : "Plus Jakarta Sans" }}', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js & Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Chart.js for Admin Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0B0D13; }
        ::-webkit-scrollbar-thumb { background: #1F2637; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #D4AF37; }
        
        .gold-glow {
            box-shadow: 0 0 25px -5px rgba(212, 175, 55, 0.15);
        }
        .text-gradient-gold {
            background: linear-gradient(135deg, #F3E5AB 0%, #D4AF37 50%, #AA7C11 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="bg-aura-dark text-gray-200 antialiased font-sans flex min-h-screen selection:bg-amber-500 selection:text-slate-950"
      x-data="{ sidebarOpen: false, profileDropdown: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm lg:hidden"></div>

    <!-- Admin Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 start-0 z-50 w-72 bg-aura-panel border-r border-aura-border flex flex-col justify-between transition-transform duration-300 ease-in-out">
        
        <div>
            <!-- Brand Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-aura-border">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-600 via-amber-400 to-yellow-200 flex items-center justify-center text-slate-950 font-black text-lg shadow-lg shadow-amber-500/20">
                        A
                    </div>
                    <div>
                        <span class="text-lg font-black tracking-widest text-white uppercase block">A U R A</span>
                        <span class="text-[9px] font-bold text-amber-500 tracking-wider uppercase block">Executive Suite</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-160px)]">
                <div class="px-4 py-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500">Core Overview</div>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center text-sm"></i>
                    <span>{{ __('app.dashboard') }}</span>
                </a>

                <div class="px-4 pt-4 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500">Commerce Engine</div>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.products.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm"></i>
                    <span>{{ __('app.products') }}</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.categories.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center text-sm"></i>
                    <span>{{ __('app.categories') }}</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.orders.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-receipt w-5 text-center text-sm"></i>
                    <span>{{ __('app.orders') }}</span>
                </a>

                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.coupons.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-ticket-simple w-5 text-center text-sm"></i>
                    <span>{{ __('app.coupons') }}</span>
                </a>

                <div class="px-4 pt-4 pb-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500">Clients & Analytics</div>

                <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.customers.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-user-tie w-5 text-center text-sm"></i>
                    <span>{{ __('app.customers') }}</span>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold transition {{ request()->routeIs('admin.reports.*') ? 'bg-amber-500 text-slate-950 shadow-lg shadow-amber-500/20' : 'text-gray-400 hover:text-white hover:bg-aura-card' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center text-sm"></i>
                    <span>{{ __('app.analytics') }}</span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer / Storefront Link -->
        <div class="p-4 border-t border-aura-border">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center justify-between px-4 py-3 rounded-2xl bg-aura-card text-xs font-bold text-gray-300 hover:text-white hover:border-amber-500/40 border border-transparent transition">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-right-from-square text-amber-500"></i> View Storefront
                </span>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-500"></i>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col lg:ms-72 min-w-0">
        <!-- Top Executive Navbar -->
        <header class="h-20 bg-aura-panel/90 backdrop-blur-md border-b border-aura-border sticky top-0 z-30 px-4 sm:px-8 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-aura-card text-gray-400 hover:text-white">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <div>
                    <h2 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">AURA Control Deck</h2>
                    <p class="text-[10px] text-amber-500 font-mono hidden sm:block">VERSION 4.2 • PRODUCTION READY</p>
                </div>
            </div>

            <!-- Topbar Actions -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Language Switcher -->
                <a href="{{ route('lang.switch', app()->getLocale() === 'en' ? 'ar' : 'en') }}"
                   class="px-3 py-1.5 rounded-xl bg-aura-card border border-aura-border text-[11px] font-bold text-gray-300 hover:text-amber-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-globe text-amber-500"></i>
                    <span>{{ app()->getLocale() === 'en' ? 'العربية' : 'English' }}</span>
                </a>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 p-1.5 rounded-2xl bg-aura-card border border-aura-border hover:border-amber-500/50 transition">
                        <img src="{{ auth()->user()->avatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" class="w-8 h-8 rounded-xl object-cover">
                        <div class="text-start pe-2 hidden md:block">
                            <span class="block text-xs font-bold text-white">{{ auth()->user()->name }}</span>
                            <span class="block text-[9px] text-amber-500 font-extrabold uppercase">Administrator</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 pe-2"></i>
                    </button>

                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute end-0 mt-2 w-48 bg-aura-card border border-aura-border rounded-2xl shadow-2xl py-2 z-50 text-xs">
                        <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-aura-panel hover:text-amber-400">
                            <i class="fa-solid fa-user-gear me-2"></i> My Account
                        </a>
                        <div class="my-1 border-t border-aura-border"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-start px-4 py-2 text-rose-400 hover:bg-rose-500/10">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> {{ __('app.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Alerts -->
        <div class="px-4 sm:px-8 pt-6">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-base"></i> {{ session('success') }}
                    </span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-bold flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-xmark text-base"></i> {{ session('error') }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
