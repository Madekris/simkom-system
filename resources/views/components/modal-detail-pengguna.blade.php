@props([
    'data'

]
)

<div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <!-- Kontainer Modal -->
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#E5E7EB]">
            <h2 class="font-bold text-[#1C1E2C]">Detail Pengguna</h2>
            <a href="{{ route('admin.pengguna.index') }}" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-[#F7F8FC] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x h-4 w-4 text-[#6B7280]">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Profil Utama (Avatar, Nama, Role, Status) -->
        <div class="px-6 pt-6 pb-4 flex items-center gap-4">
            <!-- Avatar Inisial -->
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-[#7C3AED] text-white shrink-0 font-bold text-xl">
                @php
                    // Ambil nama lengkap dari relasi yang tersedia
                    $fullName = $data->mahasiswa->nama ?? $data->pembina->nama ?? '-';
                    
                    // Logika mengambil 2 huruf inisial depan
                    $initials = '-';
                    if ($fullName !== '-') {
                        $words = explode(' ', $fullName);
                        $initials = strtoupper(substr($words[0], 0, 1)); // Huruf pertama kata pertama
                        if (count($words) > 1) {
                            $initials .= strtoupper(substr($words[1], 0, 1)); // Huruf pertama kata kedua
                        }
                    }
                @endphp
                
                <span>{{ $initials }}</span>
            </div>
            <!-- Nama & Role -->
            <div class="flex-1 min-w-0">
                <div class="font-bold text-lg text-[#1C1E2C] truncate">{{ $data->mahasiswa->nama ?? $data->pembina->nama ?? '-' }}</div>
                @php
                    // Mapping warna berdasarkan role (bg dan text)
                    $roleColors = [
                        'admin'     => 'bg-[#1A2B5C]/10 text-[#1A2B5C]',
                        'pengurus'  => 'bg-[#00C9A7]/10 text-[#0F766E]',
                        'pembina'   => 'bg-[#7C3AED]/10 text-[#6D28D9]',
                        'bendahara' => 'bg-[#F5A623]/15 text-[#92400E]',
                        'mahasiswa' => 'bg-[#E5E7EB] text-[#374151]' // Fallback / Tambahan jika ada mahasiswa
                    ];

                    // Ambil string role (jadikan lowercase agar pencocokan array aman)
                    $userRole = strtolower($data->role);
                    
                    // Pilih warna berdasarkan role, jika tidak terdaftar pakai warna mahasiswa (default)
                    $selectedColor = $roleColors[$userRole] ?? 'bg-[#E5E7EB] text-[#374151]';
                @endphp

                @if ($data->mahasiswa)

                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold mt-1 transition-colors {{ $selectedColor }}">
                    {{ ucfirst($data->role) }}
                </span>
                
                @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold mt-1 transition-colors {{ $selectedColor }}">
                    {{ ucfirst($data->role) }}
                </span>
                @endif
            </div>
            <!-- Status Badge -->
            @php
                $status = $data->anggotaOrganisasi->first()->status ?? 'nonaktif';
            @endphp

            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold transition-colors
                {{ $status == 'aktif' ? 'bg-[#DCFCE7] text-[#166534]' : 'bg-[#FEE2E2] text-[#991B1B]' }}">
                {{ $status }}
            </span>
        </div>

        <!-- Detail Informasi Grid -->
        <div class="px-6 pb-6 space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <!-- NIM / NIDN -->
                <div class="bg-[#F7F8FC] rounded-xl p-3">
                    <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-0.5">{{ $data->mahasiswa ? 'NIM' : 'NIP' }}</div>
                    <div class="text-sm font-semibold text-[#1C1E2C] truncate">{{ $data->mahasiswa->nim ?? $data->pembina->nip ?? '-' }}</div>
                </div>

                @if ($data->mahasiswa)
                    <!-- Program Studi -->
                    <div class="bg-[#F7F8FC] rounded-xl p-3">
                        <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-0.5">Program Studi</div>
                        <div class="text-sm font-semibold text-[#1C1E2C] truncate">{{ $data->mahasiswa->programStudi->nama ?? '-'}}</div>
                    </div>
                    <!-- Semester -->
                    <div class="bg-[#F7F8FC] rounded-xl p-3">
                        <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-0.5">Semester</div>
                        <div class="text-sm font-semibold text-[#1C1E2C] truncate">Semester {{ $data->mahasiswa->semester ?? '-' }}</div>
                    
                    </div>
                @endif
                <!-- Email -->
                <div class="bg-[#F7F8FC] rounded-xl p-3">
                    <div class="text-[10px] font-semibold text-[#6B7280] uppercase tracking-wider mb-0.5">Email</div>
                    <div class="text-sm font-semibold text-[#1C1E2C] truncate">{{ $data->email ?? '-'}}</div>
                </div>
            </div>
        </div>

        <!-- Footer / Aksi Modal -->
        <div class="px-6 py-4 border-t border-[#E5E7EB] flex justify-end">
            <a href="{{ route('admin.pengguna.index') }}" data-slot="button" class="inline-flex h-9 shrink-0 items-center justify-center rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-all outline-none hover:bg-gray-50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 disabled:pointer-events-none disabled:opacity-50 aria-invalid:border-destructive aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 has-[>svg]:px-3 [&_svg:not([class*='size-'])]:size-4 [&_svg]:pointer-events-none [&_svg]:shrink-0">
                Tutup
            </a>
        </div>

    </div>
</div>