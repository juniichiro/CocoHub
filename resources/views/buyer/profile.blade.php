@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="min-h-screen bg-[#F9F7F2] flex flex-col" 
     x-data="{ 
        photoPreview: null, 
        openDeleteModal: {{ $errors->userDeletion->any() ? 'true' : 'false' }}, 
        deleteUrl: '{{ route('profile.destroy') }}',
        modalTitle: 'Deactivate Account?',
        modalMessage: 'Are you sure you want to proceed? This will permanently remove your account history and associated data.'
     }">
    
    @include('layouts.navigation')

    <main class="flex-grow max-w-7xl mx-auto px-8 lg:px-20 py-12 w-full">
        
        <header class="mb-10">
            <span class="text-[#738D56] text-xs font-bold uppercase tracking-[0.2em]">Your Profile</span>
            <h1 class="text-4xl font-bold text-gray-900 mt-2">Manage Profile</h1>
        </header>

        @if(session('status') === 'profile-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm rounded-2xl animate-fade-in">
                Profile updated successfully.
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm rounded-2xl animate-fade-in">
                Password changed successfully.
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <div class="w-full lg:w-[55%] bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50 flex flex-col items-center">
                <div class="relative mb-12">
                    <div class="w-40 h-40 rounded-full border-4 border-[#738D56]/20 overflow-hidden shadow-lg bg-gray-50">
                        <template x-if="photoPreview">
                            <img :src="photoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!photoPreview">
                            <img src="{{ $user->buyerDetail->profile_picture ? asset('images/profile/' . $user->buyerDetail->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->buyerDetail->first_name).'&background=738D56&color=fff&size=200' }}" 
                                 alt="Profile" class="w-full h-full object-cover">
                        </template>
                    </div>
                    <button type="button" onclick="document.getElementById('profile_photo').click()" 
                            class="absolute bottom-2 right-2 bg-[#738D56] text-white p-2 rounded-full border-4 border-white shadow-md hover:bg-[#5f7547] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>

                <div class="w-full space-y-4">
                    <div class="bg-[#F9F7F2]/50 p-6 rounded-[1.5rem] border border-gray-50 flex justify-between items-center text-sm">
                        <span class="font-medium text-gray-400 uppercase tracking-widest text-[10px]">Full Name</span>
                        <span class="font-bold text-gray-700">
                            {{ $user->buyerDetail->first_name }} {{ $user->buyerDetail->middle_name }} {{ $user->buyerDetail->last_name }}
                        </span>
                    </div>

                    <div class="bg-[#F9F7F2]/50 p-6 rounded-[1.5rem] border border-gray-50 space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-[10px]">Age</span>
                            <span class="font-bold text-gray-700">{{ $user->buyerDetail->age ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-[10px]">Address</span>
                            <span class="font-bold text-gray-700 text-right max-w-[250px]">{{ $user->buyerDetail->address ?? 'No address set' }}</span>
                        </div>
                    </div>

                    <div class="bg-[#F9F7F2]/50 p-6 rounded-[1.5rem] border border-gray-50 space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-[10px]">Phone</span>
                            <span class="font-bold text-gray-700">{{ $user->buyerDetail->phone_number ?? 'No phone set' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-[10px]">Email</span>
                            <span class="font-bold text-gray-700">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="pt-4 text-center">
                        <button type="button" 
                            @click="openDeleteModal = true" 
                            class="text-[10px] font-bold text-red-400 uppercase tracking-widest hover:text-red-600 transition duration-300">
                            Deactivate My Account
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-[45%] space-y-8">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
                    <h2 class="text-lg font-bold text-[#6D4C41] mb-6">Edit Profile</h2>
                    
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = (e) => { photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            ">

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Complete Name</label>
                            <div class="grid grid-cols-3 gap-2">
                                <input type="text" name="first_name" value="{{ old('first_name', $user->buyerDetail->first_name) }}" placeholder="First" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                                <input type="text" name="middle_name" value="{{ old('middle_name', $user->buyerDetail->middle_name) }}" placeholder="Mid" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                                <input type="text" name="last_name" value="{{ old('last_name', $user->buyerDetail->last_name) }}" placeholder="Last" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-2xl text-xs font-medium focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mobile Number</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number', $user->buyerDetail->phone_number) }}" class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Age</label>
                                <input type="number" name="age" value="{{ old('age', $user->buyerDetail->age) }}" class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Shipping Address</label>
                            <input type="text" name="address" value="{{ old('address', $user->buyerDetail->address) }}" class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                        </div>

                        <button type="submit" class="w-full py-4 bg-[#738D56] text-white font-bold rounded-2xl hover:bg-[#5f7547] transition-all shadow-lg shadow-[#738D56]/10 mt-2">
                            Update Information
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50"
                     x-data="{ 
                        password: '', 
                        password_confirmation: '',
                        get isDirty() { return this.password.length > 0 || this.password_confirmation.length > 0 },
                        get passwordsMatch() { return this.password === this.password_confirmation && this.password.length > 0 },
                        get isLongEnough() { return this.password.length >= 8 }
                     }">
                    <h2 class="text-lg font-bold text-[#6D4C41] mb-6">Security</h2>
                    
                    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Current Password</label>
                            <input type="password" name="current_password" required class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all">
                            @if($errors->updatePassword->has('current_password'))
                                <p class="text-xs text-red-500 mt-1 ml-2 animate-fade-in">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">New Password</label>
                            <input type="password" 
                                   name="password" 
                                   x-model="password"
                                   required 
                                   class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all"
                                   :class="isDirty && !isLongEnough ? 'ring-2 ring-orange-200' : ''">
                            
                            <template x-if="isDirty && !isLongEnough">
                                <p class="text-[10px] text-orange-500 mt-1 ml-2 font-bold uppercase tracking-tighter">At least 8 characters required</p>
                            </template>

                            @if($errors->updatePassword->has('password'))
                                <p class="text-xs text-red-500 mt-1 ml-2 animate-fade-in">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   x-model="password_confirmation"
                                   required 
                                   class="w-full px-5 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-medium outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all"
                                   :class="isDirty && password_confirmation.length > 0 && !passwordsMatch ? 'ring-2 ring-red-200' : (passwordsMatch ? 'ring-2 ring-green-100' : '')">
                            
                            <div class="mt-2 ml-2 h-4">
                                <template x-if="isDirty && password_confirmation.length > 0 && !passwordsMatch">
                                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-tighter">Passwords do not match</p>
                                </template>
                                <template x-if="passwordsMatch">
                                    <p class="text-[10px] text-[#738D56] font-bold uppercase tracking-tighter flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Ready to update
                                    </p>
                                </template>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    :disabled="!passwordsMatch || !isLongEnough"
                                    class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition-all shadow-lg shadow-black/10 disabled:opacity-50 disabled:cursor-not-allowed">
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <x-buyer-footer />

    <x-delete-modal 
        id="openDeleteModal" 
        action="deleteUrl" 
        :requirePassword="true" 
    />
</div>

<style>
    [x-cloak] { display: none !important; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
</style>
@endsection