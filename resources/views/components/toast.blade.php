<div x-data="{ 
        show: false, 
        message: '', 
        type: 'success' 
     }"
     x-init="
        @if(session()->has('success'))
            message = '{{ session('success') }}';
            type = 'success';
            show = true;
            setTimeout(() => show = false, 5000);
        @elseif(session()->has('error') || $errors->any())
            message = '{{ session('error') ?? $errors->first() }}';
            type = 'error';
            show = true;
            setTimeout(() => show = false, 5000);
        @endif
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-4"
     x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-white rounded-xl border border-[#E5E7EB] shadow-[0_4px_20px_rgba(0,0,0,0.08)] p-4 inline-flex items-start gap-3"
     style="display: none;">
    
    <div :class="type === 'success' ? 'bg-[#22C55E]/10 text-[#22C55E]' : 'bg-[#EF4444]/10 text-[#EF4444]'" 
         class="p-2 rounded-lg shrink-0">
        
        <svg x-show="type === 'success'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>

        <svg x-show="type === 'error'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </div>

    <div class="flex-1 min-w-0 pt-0.5">
        <p :class="type === 'success' ? 'text-[#16A34A]' : 'text-[#DC2626]'" 
           class="text-sm font-bold" 
           x-text="type === 'success' ? 'Berhasil!' : 'Gagal!'"></p>
        <p class="text-xs text-[#6B7280] mt-0.5 leading-relaxed" x-text="message"></p>
    </div>

    <button @click="show = false" class="text-[#9CA3AF] hover:text-[#1C1E2C] p-1 rounded-md transition-colors shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>