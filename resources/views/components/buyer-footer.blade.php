<footer class="bg-white border-t border-gray-100 pt-16 pb-12 mt-auto">
    <div class="max-w-7xl mx-auto px-8 lg:px-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 lg:gap-24">
            
            <div class="space-y-4">
                <div class="flex items-center gap-1">
                    <span class="text-2xl font-bold text-[#6D4C41]">Coco<span class="text-2xl font-bold text-[#738D56]">Hub</span></span>
                </div>
                <p class="text-sm text-gray-400 font-medium">Developed by: Lumiere</p>
                
                <div class="pt-8">
                    <p class="text-[10px] text-[#8E8E8E] leading-relaxed max-w-xs uppercase tracking-wider font-bold">
                        For educational purposes only, and no copyright infringement is intended.
                    </p>
                    <p class="text-[10px] text-[#8E8E8E] mt-2 font-bold uppercase tracking-wider">
                        &copy; {{ date('Y') }} CocoHub. All Rights Reserved.
                    </p>
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-base uppercase tracking-wider">Shop</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('buyer.product') }}" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            All Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buyer.product', ['category' => 'Gardening']) }}" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            Gardening
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buyer.product', ['category' => 'Household']) }}" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            Household
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buyer.product', ['category' => 'Construction']) }}" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            Construction
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buyer.product', ['category' => 'Home & Living']) }}" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            Home & Living
                        </a>
                    </li>
                </ul>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-base uppercase tracking-wider">Contact</h4>
                <ul class="space-y-3">
                    <li class="text-gray-400 text-sm font-medium">Quezon City, Philippines</li>
                    <li>
                        <a href="mailto:lumiere.assist@gmail.com" class="text-gray-400 hover:text-[#738D56] text-sm transition-colors duration-200 font-medium">
                            lumiere.assist@gmail.com
                        </a>
                    </li>
                    <li class="text-gray-400 text-sm font-medium">+63 921 123 4567</li>
                </ul>
            </div>

        </div>
    </div>
</footer>