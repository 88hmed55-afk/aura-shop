@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen"
     x-data="{ 
         paymentMethod: 'card',
         cardNumber: '4532 •••• •••• 8890',
         cardExpiry: '12/28',
         cardName: 'SULTAN AL-MANSOOR'
     }">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="mb-10 text-center max-w-xl mx-auto">
            <span class="text-xs font-bold text-amber-500 tracking-widest uppercase">{{ __('app.brand_name') }} CONCIERGE</span>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-2">{{ __('app.checkout_title') }}</h1>
            <p class="text-xs text-gray-400 mt-1">End-to-end encrypted luxury checkout powered by AURA secure gateway.</p>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Main Checkout Steps (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Step 1: Shipping Information -->
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4">
                            <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center">1</div>
                            <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ __('app.shipping_information') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.full_name') }} *</label>
                                <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user() ? auth()->user()->name : '') }}" required
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.email_address') }} *</label>
                                <input type="email" name="customer_email" value="{{ old('customer_email', auth()->user() ? auth()->user()->email : '') }}" required
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.phone_number') }} *</label>
                                <input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user() ? auth()->user()->phone : '+966 50 000 0000') }}" required
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.country') }} *</label>
                                <select name="country" required class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                                    <option value="Saudi Arabia">Saudi Arabia (المملكة العربية السعودية)</option>
                                    <option value="United Arab Emirates">United Arab Emirates (الإمارات العربية المتحدة)</option>
                                    <option value="Kuwait">Kuwait (الكويت)</option>
                                    <option value="Qatar">Qatar (قطر)</option>
                                    <option value="Bahrain">Bahrain (البحرين)</option>
                                    <option value="Oman">Oman (عُمان)</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.address_line1') }} *</label>
                                <input type="text" name="address_line_1" value="{{ old('address_line_1', $savedAddress ? $savedAddress->address_line_1 : '') }}" required
                                       placeholder="King Fahd Road, Olaya District, Building 404"
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.city') }} *</label>
                                <input type="text" name="city" value="{{ old('city', $savedAddress ? $savedAddress->city : 'Riyadh') }}" required
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">{{ __('app.state') }}</label>
                                <input type="text" name="state" value="{{ old('state', $savedAddress ? $savedAddress->state : 'Riyadh Province') }}"
                                       class="w-full px-4 py-3 rounded-xl bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-xs focus:outline-none focus:border-amber-500">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Payment Selection -->
                    <div class="glass-panel p-6 sm:p-8 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6">
                        <div class="flex items-center gap-3 border-b border-gray-200 dark:border-gray-800 pb-4">
                            <div class="w-8 h-8 rounded-full bg-amber-500 text-slate-950 font-black text-xs flex items-center justify-center">2</div>
                            <h3 class="font-bold text-base text-gray-900 dark:text-white">{{ __('app.payment_method') }}</h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Card Option -->
                            <label @click="paymentMethod = 'card'"
                                   :class="{ 'border-amber-500 bg-amber-500/10': paymentMethod === 'card' }"
                                   class="p-4 rounded-2xl border border-gray-700 glass-panel cursor-pointer flex flex-col items-center justify-center gap-2 text-center transition">
                                <input type="radio" name="payment_method" value="card" class="sr-only" checked>
                                <i class="fa-solid fa-credit-card text-xl text-amber-400"></i>
                                <span class="font-bold text-xs text-white">{{ __('app.credit_card') }}</span>
                            </label>

                            <!-- Apple Pay Option -->
                            <label @click="paymentMethod = 'apple_pay'"
                                   :class="{ 'border-amber-500 bg-amber-500/10': paymentMethod === 'apple_pay' }"
                                   class="p-4 rounded-2xl border border-gray-700 glass-panel cursor-pointer flex flex-col items-center justify-center gap-2 text-center transition">
                                <input type="radio" name="payment_method" value="apple_pay" class="sr-only">
                                <i class="fa-brands fa-apple-pay text-2xl text-white"></i>
                                <span class="font-bold text-xs text-white">{{ __('app.apple_pay') }}</span>
                            </label>

                            <!-- Cash on Delivery -->
                            <label @click="paymentMethod = 'cash_on_delivery'"
                                   :class="{ 'border-amber-500 bg-amber-500/10': paymentMethod === 'cash_on_delivery' }"
                                   class="p-4 rounded-2xl border border-gray-700 glass-panel cursor-pointer flex flex-col items-center justify-center gap-2 text-center transition">
                                <input type="radio" name="payment_method" value="cash_on_delivery" class="sr-only">
                                <i class="fa-solid fa-money-bill-wave text-xl text-emerald-400"></i>
                                <span class="font-bold text-xs text-white">{{ __('app.cash_on_delivery') }}</span>
                            </label>
                        </div>

                        <!-- Live Interactive Credit Card Simulator -->
                        <div x-show="paymentMethod === 'card'" class="space-y-4 pt-2">
                            <!-- Visual Card Mockup -->
                            <div class="w-full max-w-sm mx-auto p-6 rounded-2xl bg-gradient-to-tr from-slate-950 via-slate-900 to-amber-950 text-white border border-amber-500/30 shadow-2xl space-y-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-mono tracking-widest text-amber-400">AURA PLATINUM</span>
                                    <i class="fa-solid fa-microchip text-2xl text-amber-400"></i>
                                </div>
                                <div class="text-lg font-mono tracking-widest text-center" x-text="cardNumber">4532 •••• •••• 8890</div>
                                <div class="flex justify-between text-[10px] font-mono text-gray-400">
                                    <div>
                                        <span>CARD HOLDER</span>
                                        <div class="text-xs text-white font-bold uppercase" x-text="cardName">SULTAN AL-MANSOOR</div>
                                    </div>
                                    <div>
                                        <span>EXPIRES</span>
                                        <div class="text-xs text-white font-bold" x-text="cardExpiry">12/28</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">{{ __('app.card_number') }}</label>
                                    <input type="text" x-model="cardNumber" placeholder="4532 8900 1234 8890"
                                           class="w-full px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-xs text-white font-mono focus:outline-none focus:border-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">{{ __('app.card_expiry') }}</label>
                                    <input type="text" x-model="cardExpiry" placeholder="MM/YY"
                                           class="w-full px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-xs text-white font-mono focus:outline-none focus:border-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">{{ __('app.card_cvc') }}</label>
                                    <input type="password" placeholder="•••" maxlength="4"
                                           class="w-full px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-xs text-white font-mono focus:outline-none focus:border-amber-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary & Order Confirmation (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="glass-panel p-6 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-6 sticky top-28">
                        <h3 class="font-bold text-base text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-800 pb-4">
                            {{ __('app.order_summary') }} ({{ $cartItems->sum('quantity') }})
                        </h3>

                        <!-- Items Preview List -->
                        <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar">
                            @foreach($cartItems as $item)
                                <div class="flex items-center gap-3 text-xs">
                                    <img src="{{ $item->product ? $item->product->main_image : '' }}" class="w-10 h-10 rounded-lg object-cover">
                                    <div class="flex-1 min-w-0">
                                        <h5 class="font-bold text-gray-900 dark:text-white truncate">{{ $item->product ? $item->product->name : '' }}</h5>
                                        <span class="text-gray-400">Qty: {{ $item->quantity }}</span>
                                    </div>
                                    <span class="font-bold text-amber-500">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Final Price Breakdown -->
                        <div class="space-y-2 text-xs text-gray-400 pt-4 border-t border-gray-200 dark:border-gray-800">
                            <div class="flex justify-between">
                                <span>{{ __('app.subtotal') }}</span>
                                <span class="font-bold text-white">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-400">
                                    <span>{{ __('app.discount') }}</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>{{ __('app.tax') }} (15%)</span>
                                <span class="font-bold text-white">${{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>{{ __('app.shipping') }}</span>
                                <span class="font-bold text-white">{{ $shipping == 0 ? __('app.free') : '$' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-black text-white pt-3 border-t border-gray-800">
                                <span>{{ __('app.total') }}</span>
                                <span class="text-amber-400 text-xl">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 font-extrabold text-xs uppercase tracking-wider rounded-2xl hover:from-amber-400 hover:to-amber-500 transition shadow-xl shadow-amber-500/20">
                            <i class="fa-solid fa-lock {{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }}"></i> {{ __('app.place_order') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
