<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-20">
        <div class="flex items-center justify-between h-20">
            
            <div class="flex items-center flex-1">
                <a href="{{ route('buyer.home') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/coco-hub.png') }}" alt="CocoHub Logo" class="h-10 w-10 object-contain transition-transform group-hover:scale-105">
                    <div class="hidden md:flex flex-col">
                        <h1 class="text-xl font-extrabold leading-none tracking-tight">
                            <span class="text-[#6D4C41]">Coco</span><span class="text-[#738D56]">Hub</span>
                        </h1>
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mt-0.5">Lumiere</span>
                    </div>
                </a>
            </div>

            <div class="hidden sm:flex items-center justify-center space-x-8">
                <x-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')" class="text-sm font-bold">
                    {{ __('Home') }}
                </x-nav-link>

                <x-nav-link :href="route('buyer.about')" :active="request()->routeIs('buyer.about')" class="text-sm font-bold">
                    {{ __('About') }}
                </x-nav-link>

                <x-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')" class="text-sm font-bold">
                    {{ __('Products') }}
                </x-nav-link>

                @auth
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
                @endauth
            </div>

            <div class="hidden sm:flex items-center justify-end flex-1">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 border border-gray-100 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 hover:border-red-100 transition duration-300 text-sm font-bold group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-2 px-8 py-2.5 bg-[#738D56] text-white rounded-2xl hover:bg-[#5f7547] transition duration-300 text-sm font-bold shadow-lg shadow-[#738D56]/20">
                        Login
                    </a>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#738D56] hover:bg-gray-50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100 animate-fade-in" x-cloak>
        <div class="pt-2 pb-6 space-y-1 px-4">
            <x-responsive-nav-link :href="route('buyer.home')" :active="request()->routeIs('buyer.home')" class="font-bold">
                {{ __('Home') }}
            </x-responsive-nav-link>
            
            <x-responsive-nav-link :href="route('buyer.about')" :active="request()->routeIs('buyer.about')" class="font-bold">
                {{ __('About') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('buyer.product')" :active="request()->routeIs('buyer.product')" class="font-bold">
                {{ __('Products') }}
            </x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('buyer.cart')" :active="request()->routeIs('buyer.cart')" class="font-bold">
                    {{ __('Cart') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('buyer.checkout')" :active="request()->routeIs('buyer.checkout')" class="font-bold">
                    {{ __('Checkout') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('buyer.history')" :active="request()->routeIs('buyer.history')" class="font-bold">
                    {{ __('History') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" class="font-bold">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
            @endauth
            
            <div class="border-t border-gray-100 mt-4 pt-4 px-3">
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 py-3 text-sm font-bold text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Log Out') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full flex items-center gap-3 py-3 text-sm font-bold text-[#738D56] hover:bg-gray-50 rounded-xl transition-colors">
                        {{ __('Log In') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>