<div x-show="cartDrawerOpen" x-cloak class="relative z-50">
    <!-- Backdrop Overlay -->
    <div x-show="cartDrawerOpen" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="cartDrawerOpen = false"
         class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

    <div class="fixed inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} max-w-full flex pl-10">
        <div x-show="cartDrawerOpen"
             x-transition:enter="transform transition ease-in-out duration-300"
             x-transition:enter-start="{{ app()->getLocale() === 'ar' ? '-translate-x-full' : 'translate-x-full' }}"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-300"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="{{ app()->getLocale() === 'ar' ? '-translate-x-full' : 'translate-x-full' }}"
             class="w-screen max-w-md bg-gray-900 text-gray-100 border border-gray-800 shadow-2xl flex flex-col"
             x-data="{
                 cartData: { items: [], count: 0, subtotal: '0.00', discount: '0.00', tax: '0.00', shipping: '0.00', total: '0.00' },
                 loading: false,
                 couponInput: '',
                 fetchCart() {
                     this.loading = true;
                     fetch('{{ route('cart.drawer') }}')
                         .then(res => res.json())
                         .then(data => {
                             this.cartData = data || { items: [], count: 0 };
                             this.loading = false;
                             $dispatch('cart-updated', { count: data.count || 0 });
                         })
                         .catch(() => { this.loading = false; });
                 },
                 updateQty(id, qty) {
                     fetch('/cart/update/' + id, {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({ quantity: qty })
                     }).then(() => this.fetchCart());
                 },
                 removeItem(id) {
                     fetch('/cart/remove/' + id, {
                         method: 'DELETE',
                         headers: {
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         }
                     }).then(() => this.fetchCart());
                 },
                 applyCoupon() {
                     if (!this.couponInput) return;
                     fetch('{{ route('cart.coupon') }}', {
                         method: 'POST',
                         headers: {
                             'Content-Type': 'application/json',
                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
                         },
                         body: JSON.stringify({ code: this.couponInput })
                     }).then(res => res.json()).then(res => {
                         if (res.success) {
                             this.fetchCart();
                             $dispatch('toast', { message: res.message, type: 'success' });
                             this.couponInput = '';
                         } else {
                             $dispatch('toast', { message: res.message, type: 'error' });
                         }
                     });
                 }
             }"
             x-init="fetchCart(); $watch('cartDrawerOpen', val => { if(val) fetchCart() })">

            <!-- Drawer Header -->
            <div class="px-6 py-5 border-b border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-bag-shopping text-amber-400 text-xl"></i>
                    <h3 class="font-bold text-lg text-white">{{ __('app.shopping_cart') }}</h3>
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-400 text-xs font-bold" x-text="cartData.count + ' items'"></span>
                </div>
                <button @click="cartDrawerOpen = false" class="p-2 rounded-full text-gray-400 hover:text-white hover:bg-gray-800 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Items List -->
            <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar space-y-4">
                <template x-if="cartData.items.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center text-gray-500 mb-4 text-2xl">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <p class="text-gray-400 font-medium text-sm mb-4">{{ __('app.empty_cart') }}</p>
                        <a href="{{ route('shop') }}" @click="cartDrawerOpen = false" class="px-6 py-2.5 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-400 transition">
                            {{ __('app.continue_shopping') }}
                        </a>
                    </div>
                </template>

                <template x-for="item in cartData.items" :key="item.id">
                    <div class="flex gap-4 p-3 rounded-2xl bg-gray-800/60 border border-gray-800 items-center">
                        <img :src="item.image" class="w-16 h-16 rounded-xl object-cover border border-gray-700">
                        <div class="flex-1 min-w-0">
                            <h4 x-text="item.name" class="font-bold text-xs text-white truncate"></h4>
                            <p class="text-amber-400 font-bold text-xs mt-1" x-text="'$' + item.price"></p>
                            <div class="flex items-center gap-2 mt-2">
                                <button @click="updateQty(item.id, Math.max(1, item.quantity - 1))" class="w-6 h-6 rounded bg-gray-700 text-gray-300 text-xs hover:bg-amber-500 hover:text-slate-950">-</button>
                                <span x-text="item.quantity" class="text-xs font-semibold px-1"></span>
                                <button @click="updateQty(item.id, item.quantity + 1)" class="w-6 h-6 rounded bg-gray-700 text-gray-300 text-xs hover:bg-amber-500 hover:text-slate-950">+</button>
                            </div>
                        </div>
                        <button @click="removeItem(item.id)" class="text-gray-500 hover:text-rose-400 p-2 text-xs">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </template>
            </div>

            <!-- Drawer Footer Summary -->
            <template x-if="cartData.items.length > 0">
                <div class="p-6 border-t border-gray-800 bg-gray-950/80 space-y-4">
                    <!-- Coupon Input -->
                    <div class="flex gap-2">
                        <input type="text" x-model="couponInput" placeholder="{{ __('app.coupon_code') }}"
                               class="flex-1 px-3 py-2 rounded-xl bg-gray-900 border border-gray-800 text-xs text-white uppercase focus:outline-none focus:border-amber-500">
                        <button @click="applyCoupon()" class="px-4 py-2 bg-amber-500/20 text-amber-400 hover:bg-amber-500 hover:text-slate-950 text-xs font-bold rounded-xl transition">
                            {{ __('app.apply_coupon') }}
                        </button>
                    </div>

                    <!-- Breakdown -->
                    <div class="space-y-1.5 text-xs text-gray-400">
                        <div class="flex justify-between">
                            <span>{{ __('app.subtotal') }}</span>
                            <span class="text-white font-medium" x-text="'$' + cartData.subtotal"></span>
                        </div>
                        <template x-if="parseFloat(cartData.discount) > 0">
                            <div class="flex justify-between text-emerald-400">
                                <span>{{ __('app.discount') }}</span>
                                <span x-text="'-$' + cartData.discount"></span>
                            </div>
                        </template>
                        <div class="flex justify-between">
                            <span>{{ __('app.shipping') }}</span>
                            <span class="text-white font-medium" x-text="cartData.shipping"></span>
                        </div>
                        <div class="flex justify-between font-bold text-sm text-white pt-2 border-t border-gray-800">
                            <span>{{ __('app.total') }}</span>
                            <span class="text-amber-400 text-base" x-text="'$' + cartData.total"></span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a href="{{ route('cart.index') }}" @click="cartDrawerOpen = false"
                           class="py-3 px-4 rounded-xl border border-gray-700 text-center text-xs font-bold text-gray-300 hover:bg-gray-800 transition">
                            View Full Cart
                        </a>
                        <a href="{{ route('checkout.index') }}" @click="cartDrawerOpen = false"
                           class="py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 text-center text-xs font-extrabold uppercase hover:from-amber-400 hover:to-amber-500 transition shadow-lg shadow-amber-500/20">
                            {{ __('app.checkout') }}
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
