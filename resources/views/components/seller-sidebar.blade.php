<aside class="w-64 bg-white border-r border-gray-100 flex flex-col fixed h-full z-10">
    <div class="p-8">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-[#F9F7F2] rounded-xl flex items-center justify-center">
                <img src="{{ asset('images/coco-hub.png') }}" class="w-6 h-6" alt="Logo">
            </div>
            <div>
                <span class="text-l font-bold text-[#6D4C41]">Coco<span class="text-l font-bold text-[#738D56]">Hub</span></span>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Seller's Portal</p>
            </div>
        </div>
    </div>

    <div class="px-6 mb-8">
        <div class="bg-[#F9F7F2] p-4 rounded-2xl flex items-center gap-3 border border-gray-50">
            <img src="{{ Auth::user()->sellerDetail->profile_picture 
                        ? asset('images/' . Auth::user()->sellerDetail->profile_picture) 
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->sellerDetail->first_name) . '&background=738D56&color=fff' }}" 
                 class="w-10 h-10 rounded-xl object-cover shadow-sm border border-white" 
                 alt="Seller Profile">
            
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-gray-800 truncate">
                    {{ Auth::user()->sellerDetail->first_name ?? 'Seller' }}
                </p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">Administrator</p>
            </div>
        </div>
    </div>

    <nav class="flex-grow px-4 space-y-2">
        <x-sidebar-link :href="route('seller.dashboard')" :active="request()->routeIs('seller.dashboard')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
            <span class="text-sm font-bold">Dashboard</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('seller.storefront')" :active="request()->routeIs('seller.storefront')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-sm font-bold">Storefront</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('seller.inventory')" :active="request()->routeIs('seller.inventory')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            <span class="text-sm font-bold">Inventory</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('seller.orders')" :active="request()->routeIs('seller.orders')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span class="text-sm font-bold">Orders</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('seller.sales')" :active="request()->routeIs('seller.sales')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            <span class="text-sm font-bold">Sales</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-sm font-bold">Profile</span>
        </x-sidebar-link>

        <x-sidebar-link :href="route('seller.clients')" :active="request()->routeIs('seller.clients')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="text-sm font-bold">Clients</span>
        </x-sidebar-link>

        {{-- NEW: Customer Reviews Link --}}
        <x-sidebar-link :href="route('seller.reviews')" :active="request()->routeIs('seller.reviews')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="text-sm font-bold">Reviews</span>
        </x-sidebar-link>
    </nav>

    <div class="p-6 border-t border-gray-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-4 py-3 w-full text-gray-400 hover:text-red-500 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="text-sm font-bold">Logout</span>
            </button>
        </form>
    </div>
</aside>