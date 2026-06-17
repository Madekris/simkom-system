<div x-data="{ open: false, fileUrl: '', isPdf: false }"
     x-on:open-preview.window="
        open = true; 
        fileUrl = $event.detail.url; 
        isPdf = $event.detail.isPdf;
     "
     x-show="open"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;"
     @keydown.escape.window="open = false"
>
    <div class="bg-white rounded-xl max-w-2xl w-full flex flex-col max-h-[85vh] shadow-xl border border-[#E5E7EB]"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    >
        <div class="px-5 py-3.5 border-b border-[#E5E7EB] flex items-center justify-between bg-[#F7F8FC] rounded-t-xl">
            <h3 class="text-sm font-bold text-[#1C1E2C] flex items-center gap-2">
                Preview Bukti Transaksi
            </h3>
            <button @click="open = false" class="text-[#9CA3AF] hover:text-[#EF4444] hover:bg-[#EF4444]/10 p-1 rounded-lg transition-all duration-200 cursor-pointer outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div class="p-5 overflow-y-auto flex justify-center items-center bg-[#F7F8FC]/50 min-h-[200px]">
            <template x-if="!isPdf">
                <img :src="fileUrl" class="max-h-[60vh] rounded-lg object-contain border border-[#E5E7EB] shadow-sm bg-white">
            </template>

            <template x-if="isPdf">
                <iframe :src="fileUrl" class="w-full h-[60vh] rounded-lg border border-[#E5E7EB] shadow-sm bg-white"></iframe>
            </template>
        </div>
    </div>
</div>