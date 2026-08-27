@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-xs text-amber-500 font-bold uppercase hover:underline flex items-center gap-2 mb-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Catalog
            </a>
            <h1 class="text-2xl font-black text-white uppercase tracking-tight">Edit Creation: {{ $product->name_en }}</h1>
        </div>
        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="px-4 py-2 rounded-2xl bg-aura-card border border-aura-border text-amber-400 font-bold text-xs hover:border-amber-500 transition flex items-center gap-2">
            <i class="fa-solid fa-eye"></i> View Live
        </a>
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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Information (Bilingual) -->
        <div class="bg-aura-card p-6 sm:p-8 rounded-3xl border border-aura-border space-y-6">
            <h3 class="text-sm font-black text-white uppercase tracking-wider border-b border-aura-border pb-4">
                1. Product Identity & Nomenclature
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Product Title (English) *</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $product->name_en) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">اسم المنتج (بالعربية) *</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $product->name_ar) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs focus:outline-none focus:border-amber-500 transition">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name_en }} ({{ $cat->name_ar }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">SKU Reference Code *</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs uppercase font-mono placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <!-- Short Descriptions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Short Intro (English)</label>
                    <textarea name="short_description_en" rows="2" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                </div>
                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">نبذة مختصرة (بالعربية)</label>
                    <textarea name="short_description_ar" rows="2" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('short_description_ar', $product->short_description_ar) }}</textarea>
                </div>
            </div>

            <!-- Full Descriptions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Full Story & Details (English)</label>
                    <textarea name="description_en" rows="4" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('description_en', $product->description_en) }}</textarea>
                </div>
                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">التفاصيل الكاملة والقصة (بالعربية)</label>
                    <textarea name="description_ar" rows="4" class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">{{ old('description_ar', $product->description_ar) }}</textarea>
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
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Compare Price ($ USD)</label>
                    <input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}"
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Stock Quantity *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5">Main High-Resolution Image URL</label>
                <div class="flex gap-4 items-center">
                    <img src="{{ $product->image_url }}" class="w-12 h-12 rounded-xl object-cover border border-aura-border shrink-0">
                    <input type="url" name="main_image_url" value="{{ old('main_image_url', $product->image_url) }}" placeholder="https://..."
                           class="w-full px-4 py-3 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                </div>
            </div>

            <div class="flex items-center gap-8 pt-4 border-t border-aura-border">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <span class="text-xs font-bold text-gray-300">Feature on Boutique Homepage</span>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <span class="text-xs font-bold text-gray-300">Active & Published</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-2xl bg-aura-panel text-gray-400 hover:text-white font-bold text-xs uppercase tracking-wider transition">Cancel</a>
            <button type="submit" class="px-8 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20">
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection
