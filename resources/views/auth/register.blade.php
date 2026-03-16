@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="h-screen w-full bg-[#F9F7F2] flex flex-col overflow-hidden" 
     x-data="{ password: '', password_confirmation: '' }">
    
    <header class="w-full py-4 px-8 lg:px-20 bg-white border-b border-gray-100 flex items-center shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/coco-hub.png') }}" alt="CocoHub Logo" class="h-10 w-10 object-contain">
            <div class="flex flex-col">
                <h1 class="text-xl font-extrabold leading-none tracking-tight">
                    <span class="text-[#6D4C41]">Coco</span><span class="text-[#738D56]">Hub</span>
                </h1>
                <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Lumiere</span>
            </div>
        </div>
    </header>

    <main class="flex-grow flex flex-col lg:flex-row items-center justify-center p-6 lg:p-12 gap-8 lg:gap-16 overflow-y-auto">
        
        <div class="hidden lg:flex w-1/2 flex-col space-y-6 max-w-2xl">
            <div>
                <span class="px-4 py-1.5 border border-gray-200 rounded-full text-[10px] tracking-widest text-gray-500 font-semibold uppercase bg-white/50">
                    Sustainable • Handcrafted • Filipino-Made
                </span>
            </div>

            <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 leading-[1.1]">
                Natural coconut <br> coir products for a <br> greener everyday <br> life.
            </h1>

            <p class="text-gray-600 text-lg max-w-md leading-relaxed">
                Discover beautifully crafted coir products for gardening, home, and sustainable living.
            </p>

            <div class="flex gap-4 pt-2">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">100%</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Natural Fiber</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">50k</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Likes</span>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex-1 max-w-[130px]">
                    <span class="block text-2xl font-bold text-gray-800">Eco</span>
                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider text-nowrap">Packaging</span>
                </div>
            </div>
        </div>

        <div class="w-full max-w-xl lg:w-[550px] shrink-0 py-8">
            <div class="bg-white p-8 lg:p-10 rounded-[2.5rem] shadow-2xl shadow-gray-200/60 border border-gray-50">
                <h2 class="text-4xl font-bold text-[#6D4C41] text-center mb-6">Registration</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="name" id="hidden_name" value="{{ old('name') }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="First Name"
                                onkeyup="updateFullName()"
                                class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('first_name') ring-2 ring-red-500 @enderror" />
                            @error('first_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name"
                                onkeyup="updateFullName()"
                                class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm" />
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required placeholder="Last Name"
                                onkeyup="updateFullName()"
                                class="w-full px-4 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('last_name') ring-2 ring-red-500 @enderror" />
                            @error('last_name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Mobile Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number') }}" required placeholder="Mobile Number"
                                class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('phone_number') ring-2 ring-red-500 @enderror" />
                            @error('phone_number') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Age</label>
                            <input type="number" name="age" value="{{ old('age') }}" required placeholder="Age"
                                class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('age') ring-2 ring-red-500 @enderror" />
                            @error('age') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" required placeholder="Address"
                            class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('address') ring-2 ring-red-500 @enderror" />
                        @error('address') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email"
                            class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('email') ring-2 ring-red-500 @enderror" />
                        @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Password</label>
                            <input type="password" name="password" x-model="password" required placeholder="Password"
                                class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm @error('password') ring-2 ring-red-500 @enderror" />
                            @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1 ml-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" x-model="password_confirmation" required placeholder="Confirm Password"
                                class="w-full px-5 py-3 bg-[#F3F4F6] border-none rounded-xl focus:ring-2 focus:ring-[#738D56] text-gray-700 placeholder-gray-400 transition-all text-sm" />
                            
                            <div class="mt-1 ml-1 h-3">
                                <p x-show="password_confirmation.length > 0 && password === password_confirmation" 
                                   class="text-green-500 text-[10px] font-bold">✓ Passwords match</p>
                                <p x-show="password_confirmation.length > 0 && password !== password_confirmation" 
                                   class="text-red-500 text-[10px] font-bold">✗ Passwords do not match</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="password !== password_confirmation || password.length < 8"
                                :class="password !== password_confirmation || password.length < 8 ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full bg-[#738D56] hover:bg-[#5f7547] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-[#738D56]/20 transition duration-300 transform active:scale-[0.98]">
                            Register
                        </button>
                    </div>

                    <p class="text-center text-[11px] text-gray-400">
                        Already have an Account? <a href="{{ route('login') }}" class="text-[#738D56] font-bold hover:underline">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    function updateFullName() {
        const first = document.getElementById('first_name').value.trim();
        const middle = document.getElementById('middle_name').value.trim();
        const last = document.getElementById('last_name').value.trim();
        
        let fullName = first;
        if (middle) fullName += ' ' + middle;
        if (last) fullName += ' ' + last;
        
        document.getElementById('hidden_name').value = fullName;
    }
</script>
@endsection