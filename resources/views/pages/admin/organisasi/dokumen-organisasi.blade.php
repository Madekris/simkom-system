<template x-for="(kegiatan, index) in ormawaKegiatans" :key="index">
    <div x-data="{ expanded: true }" class="border border-gray-200 rounded-xl overflow-hidden bg-white shadow-sm mb-4">
        
        <div @click="expanded = !expanded" class="flex items-center justify-between px-5 py-4 bg-gray-50/70 border-b border-gray-100 cursor-pointer select-none hover:bg-gray-100/50 transition">
            <div>
                <h4 class="text-sm font-bold text-gray-900" x-text="kegiatan.nama_kegiatan"></h4>
                <span class="text-[11px] text-gray-400 font-medium block mt-0.5">
                    Periode <span x-text="kegiatan.periode"></span> • <span x-text="kegiatan.dokumen.length"></span> dokumen
                </span>
            </div>
            <button class="text-gray-400 transition-transform" :class="expanded ? 'rotate-180' : ''">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        <div x-show="expanded" class="divide-y divide-gray-100 bg-white">
            <template x-for="(doc, docIndex) in kegiatan.dokumen" :key="docIndex">
                <div class="flex items-center justify-between p-4 hover:bg-gray-50/40 transition-colors">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="text-red-500 bg-red-50 p-2 rounded-lg flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate" :title="doc.nama_file" x-text="doc.nama_file"></p>
                            <span class="text-[10px] text-gray-400" x-text="doc.created_at"></span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider" 
                              :class="{
                                  'bg-blue-50 text-blue-600': doc.tipe === 'PROPOSAL',
                                  'bg-amber-50 text-amber-600': doc.tipe === 'RAB',
                                  'bg-emerald-50 text-emerald-600': doc.tipe === 'LAPORAN'
                              }"
                              x-text="doc.tipe">
                        </span>
                        <a :href="window.location.origin + '/' + doc.path_url.replace(/^\/+/, '')" 
                           target="_blank" 
                           class="px-3 py-1 text-xs font-semibold border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>
            </template>
        </div>

    </div>
</template>