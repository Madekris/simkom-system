@props([
    'data'
])

<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
    
    <div class="flex items-center gap-4 px-6 py-5 border-b border-[#E5E7EB] shrink-0">
      <div class="flex-1 min-w-0">
        <div class="font-bold text-lg text-[#1C1E2C] truncate">{{ $data->nama }}</div>
        <div class="flex items-center gap-2 mt-1 flex-wrap">
          <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#1A2B5C]/10 text-[#1A2B5C]">{{ $data->jenisOrganisasi->nama }}</span>
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">Aktif</span>
          <span class="text-xs text-[#6B7280]">{{ $data->anggotaOrganisasi->count() }} anggota</span>
        </div>
      </div>
        <a href="{{ route('admin.organisasi.index') }}" class="w-8 h-8 rounded-full hover:bg-[#F7F8FC] flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 text-[#6B7280]">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
            </svg>
        </a>
    </div>

   <div class="flex border-b border-[#E5E7EB] px-6 shrink-0">
        <a href="{{ route('admin.organisasi.index', ['id' => request('id'), 'tab' => 'informasi']) }}" 
        class="py-3 px-4 text-sm font-semibold border-b-2 transition-colors -mb-px {{ request('tab', 'informasi') == 'informasi' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Informasi
        </a>
            
        <a href="{{ route('admin.organisasi.index', ['id' => request('id'), 'tab' => 'kegiatan']) }}" 
        class="py-3 px-4 text-sm font-semibold border-b-2 transition-colors -mb-px {{ request('tab') == 'kegiatan' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Kegiatan
        </a>

        <a href="{{ route('admin.organisasi.index', ['id' => request('id'), 'tab' => 'pengurus']) }}" 
        class="py-3 px-4 text-sm font-semibold border-b-2 transition-colors -mb-px {{ request('tab') == 'pengurus' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            Pengurus
        </a>

        {{-- <a href="{{ route('admin.organisasi.index', ['id' => 1, 'tab' => 'ad_art']) }}" 
        class="py-3 px-4 text-sm font-semibold border-b-2 transition-colors -mb-px {{ request('tab') == 'ad_art' ? 'border-[#1A2B5C] text-[#1A2B5C]' : 'border-transparent text-[#6B7280] hover:text-[#1C1E2C]' }}">
            AD/ART
        </a> --}}
    </div>

    @if (request('tab') === 'informasi')
        <div class="flex-1 overflow-y-auto px-6 py-5">
        <div class="space-y-4">
            <div class="p-4 rounded-xl bg-[#F7F8FC]">
            <div class="text-xs font-semibold text-[#6B7280] uppercase tracking-wider mb-1">Deskripsi</div>
            <p class="text-sm text-[#374151] leading-relaxed">
                {{ $data->deskripsi }}
            </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl border border-[#E5E7EB]">
                <div class="flex items-center gap-2 mb-2">
                <div class="w-1.5 h-5 rounded-full bg-[#F5A623]"></div>
                <div class="text-sm font-bold text-[#1C1E2C]">Visi</div>
                </div>
                <p class="text-sm text-[#374151] leading-relaxed">
                    {{ $data->visi }}
                </p>
            </div>
            
            <div class="p-4 rounded-xl border border-[#E5E7EB]">
                <div class="flex items-center gap-2 mb-2">
                <div class="w-1.5 h-5 rounded-full bg-[#00C9A7]"></div>
                <div class="text-sm font-bold text-[#1C1E2C]">Misi</div>
                </div>
                <p class="text-sm text-[#374151] leading-relaxed whitespace-pre-line">
                {{ $data->misi }}
                </p>
            </div>
            </div>
        </div>
        </div>
    @elseif (request('tab') === 'kegiatan')
        <div class="flex-1 overflow-y-auto px-6 py-5">
            <div class="space-y-2">
                
                {{-- Pastikan relasi 'kegiatan' sudah di-load di Controller/Model --}}
                @forelse ($data->kegiatan as $kegiatan)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-[#F7F8FC] border border-gray-100/50 hover:border-gray-200 transition-colors">
                        <div>
                            <div class="font-semibold text-sm text-[#1C1E2C]">{{ $kegiatan->judul_kegiatan }}</div>
                            <div class="text-xs text-[#6B7280]">
                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        </div>

                        @if($kegiatan->status === 'Pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                Pending
                            </span>
                        @elseif($kegiatan->status === 'Mendatang')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                Mendatang
                            </span>
                        @elseif($kegiatan->status === 'Berlangsung')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-700 border border-teal-200">
                                Berlangsung
                            </span>
                        @elseif($kegiatan->status === 'Selesai')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                Selesai
                            </span>
                        @elseif($kegiatan->status === 'Dibatalkan')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                Dibatalkan
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                                {{ $kegiatan->status }}
                            </span>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm text-gray-400">Belum ada agenda kegiatan yang terdaftar.</p>
                    </div>
                @endforelse

            </div>
        </div>
    @elseif (request('tab') === 'pengurus')
        <div class="flex-1 overflow-y-auto px-6 py-5">
            <div class="space-y-3">
            
                <div class="flex items-center gap-3 p-4 rounded-xl bg-[#F7F8FC] border border-gray-100/50 hover:border-gray-200 transition-colors">
                    
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center shrink-0 bg-[#1A2B5C] text-white font-bold text-sm border border-[#E5E7EB]">
                        
                            {{-- Mengambil maksimal 2 huruf inisial dari nama secara murni lewat PHP Blade --}}
                        @php

                                $initials = '';
                            if($data->ketua) {
                                $words = explode(' ', $data->ketua->user->mahasiswa->nama);
                                $initials = strtoupper(substr($words[0], 0, 1));
                                if (count($words) > 1) {
                                    $initials .= strtoupper(substr($words[1], 0, 1));
                                }

                            }
                        @endphp
                        <span>{{ $initials }}</span>
                       
                    </div>

                    <div>
                        <div class="font-semibold text-sm text-[#1C1E2C]">{{ $data->ketua->user->mahasiswa->nama ?? 'Belum ditentukan' }}</div>
                        <div class="text-xs text-[#6B7280]">{{ $data->ketua->jabatan ?? 'ketua' }}</div>
                    </div>

                </div>

                <div class="flex items-center gap-3 p-4 rounded-xl bg-[#F7F8FC] border border-gray-100/50 hover:border-gray-200 transition-colors">
                    
                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center shrink-0 bg-[#1A2B5C] text-white font-bold text-sm border border-[#E5E7EB]">
                        
                            {{-- Mengambil maksimal 2 huruf inisial dari nama secara murni lewat PHP Blade --}}
                        @php
                            $initials = '';
                            if($data->pembina){
                                $words = explode(' ', $data->pembina->user->pembina->nama);
                                $initials = strtoupper(substr($words[0], 0, 1));
                                if (count($words) > 1) {
                                    $initials .= strtoupper(substr($words[1], 0, 1));
                                }

                            }
                        @endphp
                        <span>{{ $initials }}</span>
                       
                    </div>

                    <div>
                        <div class="font-semibold text-sm text-[#1C1E2C]">{{ $data->pembina->user->pembina->nama ?? 'Belum ditentukan'}}</div>
                        <div class="text-xs text-[#6B7280]">{{ $data->pembina->jabatan ?? 'Pembina' }}</div>
                    </div>

                </div>

            </div>
        </div>
    @endif

    <div class="px-6 py-4 border-t border-[#E5E7EB] shrink-0 flex justify-end">
      <a href="{{ route('admin.organisasi.index') }}" data-slot="button" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-all border bg-background text-foreground hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
        Tutup
      </a>
    </div>
  </div>
</div>