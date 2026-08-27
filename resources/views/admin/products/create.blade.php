@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-amber-500 font-bold uppercase hover:underline flex items-center gap-2 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Catalog
            </a>
            <h1 class="text-2xl font-black text-white uppercase tracking-tight">Add New Luxury Creation</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- General Information (Bilingual) -->
        <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                1. Product Identity & Nomenclature
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Product Title (English) *</label>
                    <input type="text" name="name_en" value="{{ old('name_en') }}" required placeholder="e.g. Royal Chronograph Tourbillon"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">اسم المنتج (بالعربية) *</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar') }}" required placeholder="مثال: ساعة توربيون ملكية فاخرة"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs focus:outline-none focus:border-amber-500 transition">
                        <option value="">Select Atelier Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name_en }} ({{ $cat->name_ar }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">SKU Reference Code *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="e.g. AURA-WAT-099"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs uppercase font-mono placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <!-- Short Descriptions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Short Intro (English)</label>
                    <textarea name="short_description_en" rows="2" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('short_description_en') }}</textarea>
                </div>
                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">نبذة مختصرة (بالعربية)</label>
                    <textarea name="short_description_ar" rows="2" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('short_description_ar') }}</textarea>
                </div>
            </div>

            <!-- Full Descriptions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Full Story & Details (English)</label>
                    <textarea name="description_en" rows="4" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('description_en') }}</textarea>
                </div>
                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">التفاصيل الكاملة والقصة (بالعربية)</label>
                    <textarea name="description_ar" rows="4" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('description_ar') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Pricing, Inventory, Visual Media -->
        <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                2. Pricing, Inventory & Imagery
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Price ($ USD) *</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="12500.00"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Compare Price ($ USD)</label>
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price') }}" placeholder="15000.00"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Main High-Resolution Image URL *</label>
                <input type="url" name="main_image_url" value="{{ old('main_image_url', 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?w=800&auto=format&fit=crop&q=80') }}" required placeholder="https://..."
                       class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
            </div>

            <div class="flex items-center gap-8 pt-4 border-t border-aura-border">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <span class="text-xs font-bold text-gray-300">Feature on Boutique Homepage</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <span class="text-xs font-bold text-gray-300">Active & Published</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-2xl bg-aura-panel text-gray-400 hover:text-white font-bold text-xs uppercase tracking-wider transition">Cancel</a>
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                Publish Product
            </button>
        </div>
    </form>
</div>
@endsection
