@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="flex min-h-screen bg-[#F9F7F2]" 
     x-data="{ 
        openDeleteModal: false, 
        deleteUrl: '', 
        modalTitle: '', 
        modalMessage: '' 
     }">
    
    <x-seller-sidebar />

    <main class="flex-grow ml-64 p-12 flex flex-col">
        <div class="flex-grow">
            <header class="mb-10">
                <p class="text-[#738D56] text-xs font-bold uppercase tracking-widest mb-1">Your Profile</p>
                <h2 class="text-3xl font-bold text-gray-900">Manage Accounts</h2>
            </header>

            @if(session('status') === 'account-deleted' || session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-[#738D56] text-sm rounded-2xl animate-fade-in">
                    {{ session('status') === 'account-deleted' ? 'Account removed successfully.' : session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-50">
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-400">Manage user accounts and permissions</h3>
                </div>

                <form method="GET" action="{{ route('seller.clients') }}" class="mb-8">
                    <div class="relative w-full">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by name, email, or city..." 
                            class="w-full px-7 py-4 bg-[#F3F4F6]/50 border-none rounded-[1.5rem] text-sm outline-none focus:ring-2 focus:ring-[#738D56]/20 transition-all shadow-sm">
                        
                        <div class="absolute right-6 top-1/2 -translate-y-1/2 flex items-center gap-3">
                            @if(request('search'))
                                <a href="{{ route('seller.clients') }}" class="text-xs font-bold text-gray-400 hover:text-[#738D56] transition-colors mr-2">
                                    Clear Search
                                </a>
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#738D56]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                <th class="pb-4 font-bold">Name</th>
                                <th class="pb-4 font-bold">Email</th>
                                <th class="pb-4 font-bold">Phone</th>
                                <th class="pb-4 font-bold text-center">Address</th>
                                <th class="pb-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($clients as $client)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                                <td class="py-5 font-medium text-gray-700">
                                    {{ $client->buyerDetail->first_name ?? 'N/A' }} 
                                    {{ $client->buyerDetail->middle_name ? $client->buyerDetail->middle_name . ' ' : '' }}
                                    {{ $client->buyerDetail->last_name ?? '' }}
                                </td>
                                <td class="py-5 text-gray-500">{{ $client->email }}</td>
                                <td class="py-5 text-gray-500">{{ $client->buyerDetail->phone_number ?? 'No Phone' }}</td>
                                <td class="py-5 text-gray-500 text-center text-xs max-w-[150px] truncate" title="{{ $client->buyerDetail->address }}">
                                    {{ $client->buyerDetail->address ?? 'No Address' }}
                                </td>
                                <td class="py-5 text-right">
                                    <div class="flex justify-end items-center">
                                        <button type="button" 
                                            @click="
                                                openDeleteModal = true; 
                                                deleteUrl = '{{ route('seller.clients.destroy', $client->id) }}';
                                                modalTitle = 'Remove Account?';
                                                modalMessage = 'Are you sure you want to delete the account for {{ $client->buyerDetail->first_name }}? this will remove all their data from the platform.';
                                            "
                                            class="text-gray-400 hover:text-red-500 transition-colors p-1" 
                                            title="Delete Account">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="font-medium">No accounts found matching your criteria.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex justify-between items-center border-t border-gray-50 pt-6">
                    <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">
                        Showing {{ $clients->count() }} {{ Str::plural('account', $clients->count()) }}
                    </p>
                </div>
            </div>
        </div>

        <x-seller-footer />
    </main>

    <x-delete-modal id="openDeleteModal" action="deleteUrl" />
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