<div id="modalDokumenProgress" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
    
    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-xl border border-gray-100 flex flex-col max-h-[90vh]">
        
        <div class="flex items-start justify-between p-6 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-[#1A2B5C] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-gray-900">Dokumen Progress</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Proposal, LPJ, RAB, dan dokumen lainnya per kegiatan</p>
                </div>
            </div>
            <button onclick="toggleModalProgress()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-lg hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto space-y-6">

            <div class="border border-gray-200 rounded-xl overflow-hidden bg-[#F9FAFB]/30">
                <div class="flex items-center justify-between px-5 py-4 bg-gray-50/70 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Workshop AI 2026</h4>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5">Periode 2025/2026 • 3 dokumen</span>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div class="divide-y divide-gray-100 bg-white">
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="far fa-file-pdf text-red-500 text-lg flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" title="Proposal_Workshop_AI_2026.pdf">Proposal_Workshop_AI_2026.pdf</p>
                                <span class="text-[10px] text-gray-400">1 Mei 2026 • 1.2 MB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 uppercase">Proposal</span>
                            <a href="#" class="px-3 py-1 text-xs font-medium border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="far fa-file-pdf text-red-500 text-lg flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" title="RAB_Workshop_AI_2026.pdf">RAB_Workshop_AI_2026.pdf</p>
                                <span class="text-[10px] text-gray-400">1 Mei 2026 • 0.5 MB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-50 text-amber-600 border border-amber-100 uppercase">RAB</span>
                            <a href="#" class="px-3 py-1 text-xs font-medium border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="far fa-file-pdf text-red-500 text-lg flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" title="LPJ_Workshop_AI_2026.pdf">LPJ_Workshop_AI_2026.pdf</p>
                                <span class="text-[10px] text-gray-400">20 Mei 2026 • 2.1 MB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">LPJ</span>
                            <a href="#" class="px-3 py-1 text-xs font-medium border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-xl overflow-hidden bg-[#F9FAFB]/30">
                <div class="flex items-center justify-between px-5 py-4 bg-gray-50/70 border-b border-gray-100">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900">Hackathon Semester Ganjil</h4>
                        <span class="text-[11px] text-gray-400 font-medium block mt-0.5">Periode 2024/2025 • 2 dokumen</span>
                    </div>
                    <button class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                <div class="divide-y divide-gray-100 bg-white">
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="far fa-file-pdf text-red-500 text-lg flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" title="Proposal_Hackathon_2024.pdf">Proposal_Hackathon_2024.pdf</p>
                                <span class="text-[10px] text-gray-400">10 Sep 2024 • 1.5 MB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 text-slate-600 uppercase">Proposal</span>
                            <a href="#" class="px-3 py-1 text-xs font-medium border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 hover:bg-gray-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <i class="far fa-file-pdf text-red-500 text-lg flex-shrink-0"></i>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate" title="LPJ_Hackathon_2024.pdf">LPJ_Hackathon_2024.pdf</p>
                                <span class="text-[10px] text-gray-400">30 Nov 2024 • 3.2 MB</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-50 text-emerald-600 border border-emerald-100 uppercase">LPJ</span>
                            <a href="#" class="px-3 py-1 text-xs font-medium border border-gray-200 rounded-md text-gray-600 bg-white hover:bg-gray-50 shadow-sm transition-colors">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
            <button onclick="toggleModalProgress()" class="px-5 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>