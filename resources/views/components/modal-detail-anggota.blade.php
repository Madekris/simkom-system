@props([
    'data'
])

<div class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col">
    
    <div class="flex items-center gap-3 px-6 py-4 border-b border-[#E5E7EB] shrink-0">
      <div class="w-10 h-10 rounded-xl bg-[#1A2B5C] text-white flex items-center justify-center shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-5 h-5">
          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
          <circle cx="9" cy="7" r="4"></circle>
          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
        </svg>
      </div>
      <div class="flex-1">
        <div class="font-bold text-[#1C1E2C]">Daftar Anggota</div>
        <div class="text-xs text-[#6B7280]">{{ $data->nama }} · {{ $data->anggotaOrganisasi->count() }} anggota</div>
      </div>
      <a href="{{ route('pembina.ormawa-binaan.index') }}" class="w-8 h-8 rounded-full hover:bg-[#F7F8FC] flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 text-[#6B7280]">
          <path d="M18 6 6 18"></path>
          <path d="m6 6 12 12"></path>
        </svg>
        </a>
    </div>

    <div class="flex-1 overflow-y-auto">
      <table class="w-full text-sm">
        <thead class="bg-[#F7F8FC] text-[#6B7280] sticky top-0">
          <tr>
            <th class="text-left px-5 py-3 font-semibold">Nama</th>
            <th class="text-left px-5 py-3 font-semibold">NIM</th>
            <th class="text-left px-5 py-3 font-semibold">Prodi</th>
            <th class="text-left px-5 py-3 font-semibold">Smt</th>
            <th class="text-left px-5 py-3 font-semibold">Status</th>
            <th class="text-right px-5 py-3 font-semibold">Ubah Status</th>
          </tr>
        </thead>
        <tbody>

            @forelse ($data->anggotaOrganisasi as $d)
                @if ($d->user->role !== 'pembina')
                    
                <tr class="border-t border-[#E5E7EB]">
                    <td class="px-5 py-3 font-semibold text-[#1C1E2C]">
                    <div class="flex items-center gap-2">
                        {{ $d->user->mahasiswa->nama ?? '-' }}
                    </div>
                    </td>
                    <td class="px-5 py-3 text-[#6B7280]"> {{ $d->user->mahasiswa->nim ?? '-' }}</td>
                    <td class="px-5 py-3 text-[#6B7280]"> {{ $d->user->mahasiswa->programStudi->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-[#6B7280]">  {{ $d->user->mahasiswa->semester ?? '-' }}</td>
                    <td class="px-5 py-3">
                        @if($d->status == 'aktif')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#DCFCE7] text-[#166534]">
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#F3F4F6] text-[#374151]">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">

                    <form action="{{ route('pembina.setStatus.setStatus', ['id' => $d->user->id]) }}" method="POST">
                        @csrf
                        @method('POST') 
                        <button type="submit" 
                            class="inline-flex items-center justify-center whitespace-nowrap text-sm font-medium 
                                transition-all duration-200 ease-in-out
                                disabled:pointer-events-none disabled:opacity-50 shrink-0 outline-none 
                                focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] 
                                h-8 rounded-md px-3 text-[#1A2B5C] gap-1 
                                hover:bg-blue-50 hover:text-blue-700 active:scale-95
                                [&_svg]:pointer-events-none [&_svg]:shrink-0"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-refresh-cw w-3.5 h-3.5">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                <path d="M21 3v5h-5"></path>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                <path d="M8 16H3v5"></path>
                            </svg>
                            Ubah
                        </button>
                    </form>
                    </td>
                </tr>
   
                @endif
                
            @empty
                <tr>
                <td colspan="6" class="px-5 py-12 text-center">
                    <div class="flex flex-col items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="h-10 w-10 text-gray-400">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <line x1="17" x2="22" y1="8" y2="13"/>
                            <line x1="22" x2="17" y1="8" y2="13"/>
                        </svg>
                        <p class="text-sm font-medium text-[#1C1E2C]">Tidak ada data anggota</p>
                        <p class="text-xs text-gray-500">Belum ada mahasiswa atau pengurus yang terdaftar dalam daftar ini.</p>
                    </div>
                </td>
            </tr>
            @endforelse

          </tbody>
      </table>
    </div>

    <div class="px-6 py-4 border-t border-[#E5E7EB] flex justify-end shrink-0">
      <a href="{{ route('pembina.ormawa-binaan.index') }}" class="inline-flex items-center justify-center gap-2 rounded-md text-sm font-medium border bg-background px-4 py-2 hover:bg-accent">
        Tutup
      </a>
    </div>
  </div>
</div>