@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{ createModal: false, editModal: false, activeCat: {} }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-white uppercase tracking-tight">{{ __('app.categories') }} & Ateliers</h1>
            <p class="text-xs text-gray-400 mt-1">Organize luxury collections into specialized departments.</p>
        </div>
        <button @click="createModal = true" class="px-5 py-3 rounded-2xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> New Category
        </button>
    </div>

    <!-- Category Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <div class="bg-aura-card rounded-3xl border border-aura-border overflow-hidden flex flex-col justify-between group hover:border-amber-500/40 transition">
                <div class="relative h-44 overflow-hidden bg-gray-900">
                    <img src="{{ $category->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                    <div class="absolute bottom-4 start-4 end-4 flex items-end justify-between">
                        <div>
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                {{ $category->products_count }} {{ __('app.products') }}
                            </span>
                            <h3 class="text-base font-bold text-white mt-1">{{ $category->name_en }}</h3>
                            <p class="text-xs text-gray-400 font-bold" dir="rtl">{{ $category->name_ar }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <p class="text-xs text-gray-400 line-clamp-2">
                        {{ $category->description_en ?: 'Exclusive curated collection for discerning connoisseurs.' }}
                    </p>

                    <div class="flex items-center justify-between pt-4 border-t border-aura-border text-xs">
                        <span class="text-[10px] font-mono text-gray-500 uppercase tracking-widest">/{{ $category->slug }}</span>
                        <div class="flex items-center gap-2">
                            <button @click="activeCat = {{ json_encode($category) }}; editModal = true" class="p-2 rounded-xl bg-aura-panel text-gray-400 hover:text-amber-400 transition" title="Edit Category">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category? Associated products may be unassigned.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white transition" title="Delete Category">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Create Category Modal -->
    <div x-show="createModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="createModal = false"></div>
        <div class="relative bg-aura-card border border-aura-border rounded-3xl p-6 sm:p-8 max-w-lg w-full z-10 shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-aura-border pb-4">
                <h3 class="text-base font-bold text-white">Create New Luxury Category</h3>
                <button @click="createModal = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Title (English) *</label>
                    <input type="text" name="name_en" required placeholder="Fine Timepieces" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">اسم القسم (بالعربية) *</label>
                    <input type="text" name="name_ar" required placeholder="الساعات الفاخرة" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Cover Image URL</label>
                    <input type="url" name="image" placeholder="https://..." class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Description (English)</label>
                    <textarea name="description_en" rows="2" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none"></textarea>
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">الوصف (بالعربية)</label>
                    <textarea name="description_ar" rows="2" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="is_featured" name="is_featured" value="1" class="rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <label for="is_featured" class="text-xs text-gray-300">Feature on Homepage Carousel</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-aura-border">
                    <button type="button" @click="createModal = false" class="px-5 py-2.5 rounded-xl bg-aura-panel text-gray-400 text-xs font-bold hover:bg-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="editModal = false"></div>
        <div class="relative bg-aura-card border border-aura-border rounded-3xl p-6 sm:p-8 max-w-lg w-full z-10 shadow-2xl space-y-6">
            <div class="flex items-center justify-between border-b border-aura-border pb-4">
                <h3 class="text-base font-bold text-white">Edit Category</h3>
                <button @click="editModal = false" class="text-gray-400 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form :action="'{{ url('/admin/categories') }}/' + activeCat.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Title (English) *</label>
                    <input type="text" name="name_en" :value="activeCat.name_en" required class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">اسم القسم (بالعربية) *</label>
                    <input type="text" name="name_ar" :value="activeCat.name_ar" required class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Cover Image URL</label>
                    <input type="url" name="image" :value="activeCat.image" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Description (English)</label>
                    <textarea name="description_en" rows="2" :value="activeCat.description_en" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none"></textarea>
                </div>

                <div dir="rtl">
                    <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">الوصف (بالعربية)</label>
                    <textarea name="description_ar" rows="2" :value="activeCat.description_ar" class="w-full bg-aura-panel border border-aura-border rounded-xl px-4 py-2.5 text-xs text-white focus:border-amber-500 focus:outline-none"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" id="edit_is_featured" name="is_featured" value="1" :checked="activeCat.is_featured" class="rounded bg-aura-panel border-aura-border text-amber-500 focus:ring-amber-500">
                    <label for="edit_is_featured" class="text-xs text-gray-300">Feature on Homepage Carousel</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-aura-border">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-xl bg-aura-panel text-gray-400 text-xs font-bold hover:bg-gray-800 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition shadow-lg shadow-amber-500/20">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
