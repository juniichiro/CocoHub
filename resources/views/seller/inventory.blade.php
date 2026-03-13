@extends('layouts.app')

@section('title', 'Inventory')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]" 
     x-data="{ 
        openAddModal: false, 
        openEditModal: false,
        openDeleteModal: false, 
        deleteUrl: '',
        editUrl: '',
        photoPreview: null,
        editPhotoPreview: null,
        currentProduct: { name: '', category: '', price: '', stock: '', description: '', image: '' }
     }">
    
    <x-seller-sidebar />

    <main class="flex-grow ml-64 p-12 flex flex-col">
        
        <div class="flex-grow">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Seller Inventory</p>
                    <h2 class="text-3xl font-bold text-gray-900">Inventory Report</h2>
                </div>
                <div class="flex gap-4">
                    <form action="{{ route('seller.inventory') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Product" 
                               class="px-6 py-3 bg-white border border-gray-100 rounded-2xl text-sm outline-none w-64 focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                    </form>
                    <a href="{{ route('seller.inventory.export') }}" class="px-6 py-3 bg-[#738D56] text-white rounded-2xl text-sm font-bold hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/20">
                        Export Report
                    </a>
                </div>
            </div>

            @if(session('status') === 'product-added')
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm rounded-2xl animate-fade-in">
                    New product has been successfully added to the inventory.
                </div>
            @endif

            @if(session('status') === 'product-updated')
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm rounded-2xl animate-fade-in">
                    Product details have been updated successfully.
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $total = $products->count();
                    $inStock = $products->where('stock', '>', 10)->count();
                    $lowStock = $products->where('stock', '<=', 10)->where('stock', '>', 0)->count();
                    $outOfStock = $products->where('stock', '<=', 0)->count();

                    $inventoryStats = [
                        ['label' => 'Total Product', 'value' => $total, 'sub' => 'Across all categories'],
                        ['label' => 'In Stock', 'value' => $inStock, 'sub' => 'Products are healthy'],
                        ['label' => 'Low Stock', 'value' => $lowStock, 'sub' => 'Limited stock available'],
                        ['label' => 'Out of Stock', 'value' => $outOfStock, 'sub' => 'Needs replenishment'],
                    ];
                @endphp
                @foreach($inventoryStats as $stat)
                <div class="bg-white p-8 rounded-[2rem] border border-gray-50 shadow-sm">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">{{ $stat['label'] }}</p>
                    <h4 class="text-4xl font-black text-gray-900 mb-2">{{ $stat['value'] }}</h4>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-tight">{{ $stat['sub'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="flex justify-between items-end mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Inventory Management</h2>
                <button @click="openAddModal = true; photoPreview = null" class="px-8 py-3 bg-[#738D56] text-white font-bold rounded-xl shadow-lg shadow-[#738D56]/20 hover:bg-[#5f7547] transition transform active:scale-95">
                    Add New Product
                </button>
            </div>

            <div class="bg-white rounded-[2.5rem] p-10 border border-gray-50 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-4">
                        <thead>
                            <tr class="text-[11px] text-gray-300 uppercase tracking-[0.15em] font-black">
                                <th class="px-4 text-center">Preview</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($products as $p)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-4 py-4 w-24 text-center">
                                    <img src="{{ asset('images/products/' . ($p->image ?? 'placeholder.png')) }}" 
                                         class="w-12 h-12 rounded-xl mx-auto object-cover shadow-sm border border-gray-100">
                                </td>
                                <td class="py-4 font-bold text-gray-800">{{ $p->name }}</td>
                                <td class="py-4 text-gray-500 font-medium">{{ $p->category }}</td>
                                <td class="py-4 font-bold text-gray-800">₱{{ number_format($p->price, 2) }}</td>
                                <td class="py-4 font-bold text-gray-800">{{ $p->stock }}</td>
                                <td class="py-4">
                                    @php
                                        if($p->stock <= 0) { $status = 'Out of Stock'; $color = 'bg-gray-100 text-gray-500'; }
                                        elseif($p->stock <= 10) { $status = 'Low Stock'; $color = 'bg-yellow-100 text-yellow-600'; }
                                        else { $status = 'In Stock'; $color = 'bg-green-100 text-[#738D56]'; }
                                    @endphp
                                    <span class="{{ $color }} text-[10px] font-black px-3 py-1 rounded-lg uppercase tracking-tight">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="py-4">
                                    <div class="flex justify-center gap-3">
                                        <button @click="
                                            editUrl = '{{ route('seller.inventory.update', $p->id) }}';
                                            currentProduct = { 
                                                name: '{{ addslashes($p->name) }}', 
                                                category: '{{ $p->category }}', 
                                                price: '{{ $p->price }}', 
                                                stock: '{{ $p->stock }}', 
                                                description: '{{ addslashes($p->description) }}',
                                                image: '{{ asset('images/products/' . ($p->image ?? 'placeholder.png')) }}'
                                            };
                                            editPhotoPreview = null;
                                            openEditModal = true;
                                        " class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-[#738D56]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button @click="openDeleteModal = true; deleteUrl = '{{ route('seller.inventory.destroy', $p->id) }}'" 
                                                class="p-2 hover:bg-gray-100 rounded-lg transition text-gray-400 hover:text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-gray-400 font-medium italic">No products found in inventory.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-seller-footer />
    </main>

    <div x-show="openAddModal" class="fixed inset-0 z-[100] flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-md" @click="openAddModal = false"></div>
        <div class="relative bg-white rounded-[2.5rem] p-10 max-w-xl w-full mx-4 shadow-2xl border border-gray-100 overflow-y-auto max-h-[90vh]">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Add New Product</h3>
            <p class="text-sm text-gray-500 mb-8">Fill in the details to list a new coconut-based item.</p>

            <form action="{{ route('seller.inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div class="flex flex-col items-center mb-6">
                    <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center relative text-center">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </template>
                    </div>
                    <input type="file" name="image" id="prod_img" class="hidden" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                    <button type="button" @click="document.getElementById('prod_img').click()" class="mt-3 text-[10px] font-bold text-[#738D56] uppercase tracking-widest">Upload Photo</button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Product Name</label>
                        <input type="text" name="name" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Category</label>
                        <select name="category" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20 outline-none">
                            <option value="" disabled selected>Select Category</option>
                            <option value="Household">Household</option>
                            <option value="Construction">Construction</option>
                            <option value="Gardening">Gardening</option>
                            <option value="Home & Living">Home & Living</option>
                            <option value="Fashion">Fashion</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Price (₱)</label>
                        <input type="number" name="price" step="0.01" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Initial Stock</label>
                        <input type="number" name="stock" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openAddModal = false" class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl text-[11px] uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-4 bg-[#738D56] text-white font-bold rounded-2xl shadow-lg shadow-[#738D56]/20 text-[11px] uppercase tracking-widest">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="openEditModal" class="fixed inset-0 z-[100] flex items-center justify-center" x-cloak>
        <div class="fixed inset-0 bg-black/40 backdrop-blur-md" @click="openEditModal = false"></div>
        <div class="relative bg-white rounded-[2.5rem] p-10 max-w-xl w-full mx-4 shadow-2xl border border-gray-100 overflow-y-auto max-h-[90vh]">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Edit Product</h3>
            <p class="text-sm text-gray-500 mb-8">Update the details for the selected coconut product.</p>

            <form :action="editUrl" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')
                
                <div class="flex flex-col items-center mb-6">
                    <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center relative text-center">
                        <template x-if="!editPhotoPreview">
                            <img :src="currentProduct.image" class="w-full h-full object-cover">
                        </template>
                        <template x-if="editPhotoPreview">
                            <img :src="editPhotoPreview" class="w-full h-full object-cover">
                        </template>
                    </div>
                    <input type="file" name="image" id="edit_prod_img" class="hidden" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { editPhotoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                    <button type="button" @click="document.getElementById('edit_prod_img').click()" class="mt-3 text-[10px] font-bold text-[#738D56] uppercase tracking-widest">Change Photo</button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Product Name</label>
                        <input type="text" name="name" x-model="currentProduct.name" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Category</label>
                        <select name="category" x-model="currentProduct.category" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20 outline-none">
                            <option value="Household">Household</option>
                            <option value="Construction">Construction</option>
                            <option value="Gardening">Gardening</option>
                            <option value="Home & Living">Home & Living</option>
                            <option value="Fashion">Fashion</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Price (₱)</label>
                        <input type="number" name="price" x-model="currentProduct.price" step="0.01" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Stock Level</label>
                        <input type="number" name="stock" x-model="currentProduct.stock" required class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Description</label>
                    <textarea name="description" x-model="currentProduct.description" rows="3" class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-[#738D56]/20"></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" @click="openEditModal = false" class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl text-[11px] uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-4 bg-gray-900 text-white font-bold rounded-2xl shadow-lg shadow-black/20 text-[11px] uppercase tracking-widest hover:bg-black transition-all">Update Product</button>
                </div>
            </form>
        </div>
    </div>

    <x-delete-modal id="openDeleteModal" action="deleteUrl" />
</div>

<style>
    [x-cloak] { display: none !important; }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection