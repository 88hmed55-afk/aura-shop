@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 dark:bg-aura-dark min-h-screen" x-data="{ addModal: false }">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Account Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ __('app.saved_addresses') }}</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Manage your delivery destinations for faster luxury checkout.</p>
            </div>
            <button @click="addModal = true" class="px-6 py-3 rounded-2xl bg-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider hover:bg-amber-400 transition-all shadow-lg shadow-amber-500/20 flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> {{ __('app.add_new_address') }}
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="glass-panel p-4 rounded-3xl border border-gray-200 dark:border-gray-800 space-y-1">
                    <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-gray-400 hover:text-white hover:bg-gray-800/40 transition">
                        <i class="fa-solid fa-gauge-high w-5 text-center"></i> {{ __('app.dashboard') }}
                    </a>
                    <a href="{{ route('account.orders') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-gray-400 hover:text-white hover:bg-gray-800/40 transition">
                        <i class="fa-solid fa-box-archive w-5 text-center"></i> {{ __('app.my_orders') }}
                    </a>
                    <a href="{{ route('account.addresses') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                        <i class="fa-solid fa-location-dot w-5 text-center"></i> {{ __('app.saved_addresses') }}
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-gray-400 hover:text-white hover:bg-gray-800/40 transition">
                        <i class="fa-solid fa-heart w-5 text-center"></i> {{ __('app.wishlist') }}
                    </a>
                </div>
            </div>

            <!-- Address Cards Grid -->
            <div class="lg:col-span-3">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold flex items-center gap-3">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if($addresses->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($addresses as $address)
                            <div class="glass-panel p-6 rounded-3xl border {{ $address->is_default ? 'border-amber-500/50 bg-amber-500/[0.02]' : 'border-gray-200 dark:border-gray-800' }} relative flex flex-col justify-between">
                                @if($address->is_default)
                                    <span class="absolute top-4 end-4 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-500 text-slate-950">
                                        Default
                                    </span>
                                @endif

                                <div>
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="w-8 h-8 rounded-xl bg-gray-800 flex items-center justify-center text-amber-500 text-xs">
                                            <i class="fa-solid fa-map-pin"></i>
                                        </div>
                                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $address->title }}</h3>
                                    </div>

                                    <div class="space-y-1 text-xs text-gray-400">
                                        <p class="font-bold text-white">{{ $address->full_name }}</p>
                                        <p><i class="fa-solid fa-phone text-[10px] me-1 text-gray-500"></i> {{ $address->phone }}</p>
                                        <p>{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)<p>{{ $address->address_line_2 }}</p>@endif
                                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        <p class="font-semibold text-gray-300">{{ $address->country }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 pt-4 border-t border-gray-800 flex items-center justify-end gap-2">
                                    <form action="{{ route('account.address.delete', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white text-xs font-bold transition">
                                            <i class="fa-regular fa-trash-can me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="glass-panel p-12 rounded-3xl border border-gray-200 dark:border-gray-800 text-center">
                        <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center text-gray-500 mx-auto mb-4 text-2xl">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <h3 class="text-base font-bold text-white mb-1">No Saved Addresses</h3>
                        <p class="text-xs text-gray-400 mb-6 max-w-sm mx-auto">Add your shipping addresses now to save time on your next purchase.</p>
                        <button @click="addModal = true" class="px-6 py-3 rounded-2xl bg-amber-500 text-slate-950 font-black text-xs uppercase tracking-wider hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">
                            Add First Address
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Address Modal -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="addModal = false"></div>
        <div class="relative bg-gray-900 border border-gray-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full z-10 shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-gray-800 pb-4">
                <h3 class="text-lg font-bold text-white">{{ __('app.add_new_address') }}</h3>
                <button @click="addModal = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('account.address.save') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Address Title (e.g. Home, Office, Villa)</label>
                        <input type="text" name="title" required placeholder="Home" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Full Name</label>
                        <input type="text" name="full_name" required value="{{ auth()->user()->name }}" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Phone Number</label>
                        <input type="text" name="phone" required value="{{ auth()->user()->phone }}" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Street Address</label>
                        <input type="text" name="address_line_1" required placeholder="Street address or P.O. Box" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Apartment, suite, unit (optional)</label>
                        <input type="text" name="address_line_2" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">City</label>
                        <input type="text" name="city" required placeholder="Dubai / Riyadh" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">State / Province</label>
                        <input type="text" name="state" placeholder="State" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" placeholder="12345" class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Country</label>
                        <select name="country" required class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Kuwait">Kuwait</option>
                            <option value="Qatar">Qatar</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_default" name="is_default" value="1" class="rounded bg-gray-800 border-gray-700 text-amber-500 focus:ring-amber-500">
                    <label for="is_default" class="text-xs text-gray-300">Set as default delivery address</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">
                    <button type="button" @click="addModal = false" class="px-5 py-2.5 rounded-xl bg-gray-800 text-gray-300 text-xs font-bold hover:bg-gray-700 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
