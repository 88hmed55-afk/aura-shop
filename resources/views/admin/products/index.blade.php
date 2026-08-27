@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.products') }} Catalog</h1>
            <p class="text-xs text-gray-400 mt-1">Manage master luxury inventory, pricing, SKU codes, and availability.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Product
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-aura-card p-4 rounded-3xl border border-aura-border">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-6 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by English/Arabic title, SKU..."
                       class="w-full ps-10 pe-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs placeholder-gray-500 focus:outline-none focus:border-amber-500 transition">
                <i class="fa-solid fa-magnifying-glass absolute inset-y-0 start-0 flex items-center ps-4 text-gray-500 text-xs pointer-events-none"></i>
            </div>

            <div class="sm:col-span-4">
                <select name="category_id" class="w-full px-4 py-2.5 rounded-2xl bg-aura-panel border border-aura-border text-white text-xs focus:outline-none focus:border-amber-500 transition">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }} ({{ $cat->name_ar }})</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full py-2.5 rounded-2xl bg-gray-800 hover:bg-gray-700 text-white font-bold text-xs uppercase tracking-wider transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('admin.products.index') }}" class="p-2.5 rounded-2xl bg-aura-panel text-gray-400 hover:text-white text-xs flex items-center justify-center">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-aura-card rounded-3xl border border-aura-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-start text-xs">
                <thead class="bg-aura-panel/50 border-b border-aura-border text-gray-400 uppercase text-[10px] tracking-wider">
                    <tr>
                        <th class="py-4 px-6 text-start">Product</th>
                        <th class="py-4 px-6 text-start">Category</th>
                        <th class="py-4 px-6 text-start">Price</th>
                        <th class="py-4 px-6 text-start">Stock</th>
                        <th class="py-4 px-6 text-start">Status</th>
                        <th class="py-4 px-6 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aura-border">
                    @forelse($products as $product)
                        <tr class="hover:bg-aura-panel/30 transition">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $product->image_url }}" class="w-12 h-12 rounded-xl object-cover border border-aura-border shrink-0">
                                    <div>
                                        <div class="font-bold text-white text-xs">{{ $product->name_en }}</div>
                                        <div class="text-[10px] text-gray-500" dir="rtl">{{ $product->name_ar }}</div>
                                        <span class="font-mono text-[9px] text-amber-500 uppercase">SKU: {{ $product->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-gray-300">
                                <span class="px-2.5 py-1 rounded-xl bg-aura-panel border border-aura-border text-[10px] font-bold">
                                    {{ $product->category->name_en ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-bold text-amber-400">${{ number_format($product->price, 2) }}</div>
                                @if($product->compare_at_price)
                                    <div class="text-[10px] text-gray-500 line-through">${{ number_format($product->compare_at_price, 2) }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($product->stock_quantity <= 0)
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30">Out of Stock</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">Low ({{ $product->stock_quantity }})</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">{{ $product->stock_quantity }} in stock</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-emerald-400 font-bold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-gray-500 font-bold text-[10px]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Inactive
                                    </span>
                                @endif
                                @if($product->is_featured)
                                    <span class="ms-2 px-2 py-0.5 rounded text-[9px] font-black uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">Featured</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="p-2 rounded-xl bg-aura-panel text-gray-400 hover:text-white transition" title="Preview Product">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="p-2 rounded-xl bg-aura-panel text-gray-400 hover:text-amber-400 transition" title="Edit Product">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Permanently remove this creation from the catalog?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition" title="Delete Product">
                                            <i class="fa-regular fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                                No products found matching your filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-aura-border">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
