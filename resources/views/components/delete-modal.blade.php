@props([
    'id',              
    'action',          
    'requirePassword' => false
])

<div x-show="{{ $id }}" class="fixed inset-0 z-[100] flex items-center justify-center" x-cloak>
    <div class="fixed inset-0 bg-black/40 backdrop-blur-md" @click="!loading ? {{ $id }} = false : null"></div>

    <div class="relative bg-white rounded-[2.5rem] p-10 max-w-md w-full mx-4 shadow-2xl border border-gray-100"
         x-show="{{ $id }}"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-data="{ loading: false }"> 
        
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-2" x-text="modalTitle"></h3>
            <p class="text-sm text-gray-500 mb-8" x-text="modalMessage"></p>

            <form :action="deleteUrl" method="POST" @submit="loading = true">
                @csrf
                @method('DELETE')

                @if($requirePassword)
                    <div class="mb-6 text-left animate-fade-in">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Confirm Identity</label>
                        <input type="password" name="password" placeholder="Enter password" required
                               class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm outline-none focus:ring-2 focus:ring-[#738D56]/10 transition-all">
                        
                        @if($errors->userDeletion->has('password'))
                            <p class="text-xs text-red-500 mt-2 ml-2">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>
                @endif

                <div class="mb-8 p-6 bg-gray-50 rounded-[1.5rem] border border-gray-100 text-left">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-gray-900 rounded-lg flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold text-gray-900 uppercase tracking-widest">Final Confirmation</span>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed font-medium">
                        Are you sure you want to proceed? This action is permanent and irreversible.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            @click="{{ $id }} = false" 
                            :disabled="loading" 
                            class="flex-1 px-6 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl text-[11px] uppercase tracking-widest hover:bg-gray-200 transition-colors disabled:opacity-50">
                        Cancel
                    </button>
                    
                    <button type="submit" 
                            :disabled="loading" 
                            class="flex-1 px-6 py-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-500/20 text-[11px] uppercase tracking-widest hover:bg-red-600 transition-all flex items-center justify-center gap-2">
                        
                        <svg x-show="loading" x-cloak class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        
                        <span x-text="loading ? 'Deleting...' : 'Confirm Delete'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>