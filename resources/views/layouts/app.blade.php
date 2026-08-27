<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
      x-data="{ 
          darkMode: localStorage.getItem('aura_theme') ? localStorage.getItem('aura_theme') === 'dark' : true,
          cartDrawerOpen: false,
          quickViewOpen: false,
          quickViewProduct: null,
          mobileMenuOpen: false,
          searchOpen: false,
          toasts: [],
          addToast(message, type = 'success') {
              const id = Date.now();
              this.toasts.push({ id, message, type });
              setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id); }, 4000);
          }
      }"
      :class="{ 'dark': darkMode }"
      class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AURA') }} - {{ __('app.brand_tagline') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
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
                            amber: '#F59E0B',
                            dark: '#0B0F19',
                            card: '#111827',
                            lightBg: '#FAFAFB',
                            lightCard: '#FFFFFF',
                            accent: '#3B82F6'
                        }
                    },
                    fontFamily: {
                        sans: ['{{ app()->getLocale() === 'ar' ? 'Cairo' : 'Outfit' }}', 'Inter', 'sans-serif'],
                        arabic: ['Cairo', 'sans-serif'],
                        english: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <script>
        window.addToCart = function (productId, qty, dispatch) {
            return new Promise(function (resolve) {
                var token = document.querySelector('meta[name="csrf-token"]');
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('cart.add') }}', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', token ? token.content : '');
                xhr.onload = function () {
                    var res = null;
                    try { res = JSON.parse(xhr.responseText || '{}'); } catch (e) { res = null; }
                    if (res && res.success && res.drawer) {
                        dispatch('cart-updated', { count: res.drawer.count });
                        dispatch('toast', { message: res.message, type: 'success' });
                    } else if (res && res.message) {
                        dispatch('toast', { message: res.message, type: 'error' });
                    } else {
                        dispatch('toast', { message: 'Could not add item. Please try again.', type: 'error' });
                    }
                    resolve();
                };
                xhr.onerror = function () {
                    dispatch('toast', { message: 'Network error. Please try again.', type: 'error' });
                    resolve();
                };
                xhr.send(JSON.stringify({ product_id: productId, quantity: qty }));
            });
        };
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-panel {
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .light .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }
        .glow-gold {
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.25);
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 9999px;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-aura-dark dark:text-gray-100 min-h-screen flex flex-col transition-colors duration-300"
      @toast.window="addToast($event.detail.message, $event.detail.type)">

    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-amber-600 via-amber-500 to-amber-700 text-slate-950 text-xs font-semibold py-2 px-4 text-center tracking-wide flex items-center justify-between z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-slate-950 animate-ping"></span>
                <span>✨ {{ __('app.free_express_shipping') }}</span>
            </div>
            <div class="flex items-center gap-4">
                <!-- Theme Switcher -->
                <button @click="darkMode = !darkMode; localStorage.setItem('aura_theme', darkMode ? 'dark' : 'light')" 
                        class="hover:opacity-80 transition flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-950/10">
                    <i class="fa-solid" :class="darkMode ? 'fa-sun text-amber-300' : 'fa-moon text-slate-900'"></i>
                    <span x-text="darkMode ? 'Light' : 'Dark'" class="uppercase text-[10px]"></span>
                </button>
                <!-- Language Switcher -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'underline font-bold' : 'opacity-70 hover:opacity-100' }}">EN</a>
                    <span>|</span>
                    <a href="{{ route('lang.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'underline font-bold' : 'opacity-70 hover:opacity-100' }}">العربية</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 glass-panel border-b border-gray-200 dark:border-gray-800 transition-colors">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-slate-950 font-black text-xl shadow-lg shadow-amber-500/20 group-hover:scale-105 transition-transform duration-300">
                        A
                    </div>
                    <div class="flex flex-col">
                        <span class="text-2xl font-extrabold tracking-wider bg-clip-text text-transparent bg-gradient-to-r from-amber-400 via-amber-200 to-amber-500">
                            {{ __('app.brand_name') }}
                        </span>
                        <span class="text-[10px] tracking-widest uppercase text-gray-400 font-medium">LUXURY STORE</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <nav class="hidden lg:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('home') }}" class="hover:text-amber-400 transition {{ request()->routeIs('home') ? 'text-amber-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ __('app.home') }}
                    </a>
                    <a href="{{ route('shop') }}" class="hover:text-amber-400 transition {{ request()->routeIs('shop*') ? 'text-amber-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ __('app.shop') }}
                    </a>
                    <a href="{{ route('about') }}" class="hover:text-amber-400 transition {{ request()->routeIs('about') ? 'text-amber-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ __('app.about') }}
                    </a>
                    <a href="{{ route('contact') }}" class="hover:text-amber-400 transition {{ request()->routeIs('contact') ? 'text-amber-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ __('app.contact') }}
                    </a>
                    <a href="{{ route('faq') }}" class="hover:text-amber-400 transition {{ request()->routeIs('faq') ? 'text-amber-400 font-semibold' : 'text-gray-700 dark:text-gray-300' }}">
                        {{ __('app.faq') }}
                    </a>
                </nav>

                <!-- Header Actions -->
                <div class="flex items-center gap-5">
                    <!-- Instant Search Toggle -->
                    <button @click="searchOpen = !searchOpen" class="p-2.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition text-gray-700 dark:text-gray-300">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </button>

                    <!-- Wishlist Button -->
                    <a href="{{ route('wishlist.index') }}" class="relative p-2.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition text-gray-700 dark:text-gray-300">
                        <i class="fa-regular fa-heart text-lg"></i>
                        @auth
                            @if(auth()->user()->wishlists()->count() > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center">
                                    {{ auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        @endauth
                    </a>

                    <!-- Cart Drawer Trigger -->
                    <button @click="cartDrawerOpen = true; $dispatch('open-cart')" class="relative p-2.5 rounded-full bg-amber-500/10 text-amber-500 hover:bg-amber-500/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                        <span x-data="{ cartCount: 0 }" 
                              x-init="fetch('{{ route('cart.drawer') }}').then(r => r.json()).then(d => cartCount = d.count)"
                              @cart-updated.window="cartCount = $event.detail.count"
                              class="text-xs font-bold bg-amber-500 text-slate-950 px-2 py-0.5 rounded-full"
                              x-text="cartCount">0</span>
                    </button>

                    <!-- User Account / Admin Menu -->
                    @auth
                        <div class="relative" x-data="{ userMenuOpen: false }">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition">
                                <img src="{{ auth()->user()->getAvatarUrl() }}" class="w-8 h-8 rounded-full border border-amber-500/50 object-cover">
                                <span class="hidden md:inline text-xs font-semibold">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                            </button>
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-cloak
                                 class="absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-56 glass-panel rounded-2xl shadow-2xl py-2 z-50 text-sm">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-amber-500/10 text-amber-400 font-semibold">
                                        <i class="fa-solid fa-chart-line"></i> {{ __('app.admin_panel') }}
                                    </a>
                                    <div class="border-t border-gray-800 my-1"></div>
                                @endif
                                <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-amber-500/10">
                                    <i class="fa-solid fa-user"></i> {{ __('app.my_account') }}
                                </a>
                                <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-2.5 hover:bg-amber-500/10">
                                    <i class="fa-solid fa-box-open"></i> {{ __('app.my_orders') }}
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-800 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-rose-500 hover:bg-rose-500/10 font-semibold">
                                        <i class="fa-solid fa-right-from-bracket"></i> {{ __('app.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-bold text-xs tracking-wider uppercase shadow-md shadow-amber-500/20 transition">
                            <i class="fa-solid fa-user"></i> {{ __('app.login') }}
                        </a>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 rounded-lg text-gray-700 dark:text-gray-300">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Interactive Search Bar Dropdown -->
        <div x-show="searchOpen" x-cloak class="border-t border-gray-200 dark:border-gray-800 py-4 px-4 bg-gray-100/90 dark:bg-slate-900/90">
            <div class="container mx-auto max-w-2xl">
                <form action="{{ route('shop') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="q" placeholder="{{ __('app.search_placeholder') }}"
                           class="w-full pl-12 pr-12 py-3 rounded-2xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-sm focus:outline-none focus:border-amber-500">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 text-gray-400"></i>
                    <button type="submit" class="absolute right-3 px-4 py-1.5 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-400 transition">
                        Search
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Dynamic Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Slide-Over Shopping Cart Drawer Component -->
    @include('components.cart-drawer')

    <!-- Toast Notification Container -->
    <div class="fixed bottom-6 {{ app()->getLocale() === 'ar' ? 'left-6' : 'right-6' }} z-50 flex flex-col gap-3 max-w-sm pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="pointer-events-auto flex items-center gap-3 p-4 rounded-2xl shadow-2xl glass-panel border border-amber-500/30 text-sm transform transition-all duration-300 animate-slide-up">
                <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-check"></i>
                </div>
                <span x-text="toast.message" class="font-medium text-gray-200"></span>
            </div>
        </template>
    </div>

    <!-- Luxury Footer -->
    <footer class="bg-gray-950 text-gray-400 border-t border-gray-800/80 pt-16 pb-12 mt-20">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <!-- Brand Info -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-slate-950 font-black text-xl">
                            A
                        </div>
                        <span class="text-2xl font-extrabold text-white tracking-wider">{{ __('app.brand_name') }}</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed max-w-sm">
                        {{ __('app.hero_subtitle') }}
                    </p>
                    <div class="flex items-center gap-4 text-gray-400 pt-2">
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-amber-500 hover:text-slate-950 transition"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-amber-500 hover:text-slate-950 transition"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-900 flex items-center justify-center hover:bg-amber-500 hover:text-slate-950 transition"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">{{ __('app.quick_links') }}</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('shop') }}" class="hover:text-amber-400 transition">{{ __('app.shop') }}</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition">{{ __('app.about') }}</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition">{{ __('app.contact') }}</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-amber-400 transition">{{ __('app.faq') }}</a></li>
                    </ul>
                </div>

                <!-- Concierge Support -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">{{ __('app.customer_service') }}</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('policies') }}" class="hover:text-amber-400 transition">{{ __('app.policies') }}</a></li>
                        <li><a href="{{ route('wishlist.index') }}" class="hover:text-amber-400 transition">{{ __('app.wishlist') }}</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-amber-400 transition">{{ __('app.cart') }}</a></li>
                    </ul>
                </div>

                <!-- Newsletter Signup -->
                <div>
                    <h4 class="text-white font-bold text-sm tracking-wider uppercase mb-4">AURA Club</h4>
                    <p class="text-xs text-gray-400 mb-4">Subscribe for private viewings and exclusive invitations.</p>
                    <form @submit.prevent="addToast('Thank you for joining AURA Club!', 'success')" class="space-y-2">
                        <input type="email" placeholder="Enter your email" required
                               class="w-full px-4 py-2.5 rounded-xl bg-gray-900 border border-gray-800 text-xs text-white focus:outline-none focus:border-amber-500">
                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-bold text-xs rounded-xl hover:from-amber-400 hover:to-amber-500 transition">
                            Join Privilege Club
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-900 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 gap-4">
                <p>&copy; {{ date('Y') }} AURA Luxury Commerce. {{ __('app.rights_reserved') }}</p>
                <div class="flex items-center gap-6">
                    <span><i class="fa-brands fa-cc-visa text-lg text-gray-400"></i></span>
                    <span><i class="fa-brands fa-cc-mastercard text-lg text-gray-400"></i></span>
                    <span><i class="fa-brands fa-apple-pay text-xl text-gray-400"></i></span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
