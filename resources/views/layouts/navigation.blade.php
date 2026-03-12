<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-20">
        <div class="flex justify-between h-20">
            <div class="flex items-center gap-12">
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('buyer.home') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/coco-hub.png') }}" alt="CocoHub Logo" class="h-10 w-10 object-contain">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-[#738D56] leading-none text-nowrap">CocoHub</span>
                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">by Lumiere</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')" class="text-sm font-bold">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')" class="text-sm font-bold">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('buyer.cart')" :active="request()->routeIs('buyer.cart')" class="text-sm font-bold">
                        {{ __('Cart') }}
                    </x-nav-link>
                    <x-nav-link :href="route('buyer.checkout')" :active="request()->routeIs('buyer.checkout')" class="text-sm font-bold">
                        {{ __('Checkout') }}
                    </x-nav-link>
                    <x-nav-link :href="route('buyer.history')" :active="request()->routeIs('buyer.history')" class="text-sm font-bold">
                        {{ __('History') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*') || request()->routeIs('buyer.profile')" class="text-sm font-bold">
                        {{ __('Profile') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition duration-300 text-sm font-medium group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-4 border-t border-gray-100 pt-4">
                @csrf
                <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="text-red-500">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>