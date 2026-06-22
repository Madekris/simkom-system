{{--
    KOMPONEN: status-badge.blade.php
    Props: $status (string)
    Contoh Blade:  @include('components.status-badge', ['status' => $kegiatan->status])
    Contoh Alpine: @include('components.status-badge', ['status' => ':item.status'])
--}}
@php
    // Cek apakah prop status diawali titik dua (:), menandakan data dari loop Alpine.js
    $isAlpine = str_starts_with($status ?? '', ':');
    $alpineVar = $isAlpine ? ltrim($status, ':') : '';
@endphp

@if($isAlpine)
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border capitalize"
          :class="{
              'bg-red-50 text-red-700 border-red-200': {{ $alpineVar }}.toLowerCase() === 'dibatalkan' || {{ $alpineVar }}.toLowerCase() === 'ditolak' || {{ $alpineVar }}.toLowerCase() === 'defisit',
              'bg-blue-50 text-blue-700 border-blue-200': {{ $alpineVar }}.toLowerCase() === 'selesai',
              'bg-gray-50 text-gray-700 border-gray-200': {{ $alpineVar }}.toLowerCase() === 'pending' || {{ $alpineVar }}.toLowerCase() === 'menunggu' || {{ $alpineVar }}.toLowerCase() === 'draft',
              'bg-orange-50 text-orange-700 border-orange-200': {{ $alpineVar }}.toLowerCase() === 'mendatang',
              'bg-emerald-50 text-emerald-700 border-emerald-200': {{ $alpineVar }}.toLowerCase() === 'berlangsung' || {{ $alpineVar }}.toLowerCase() === 'aktif' || {{ $alpineVar }}.toLowerCase() === 'disetujui'
          }"
          x-text="{{ $alpineVar }}">
    </span>
@else
    @php
        $statusDatabase = strtolower($status ?? '');
        $styles = [
            'dibatalkan'   => 'bg-red-50 text-red-700 border-red-200',
            'ditolak'      => 'bg-red-50 text-red-700 border-red-200',
            'defisit'      => 'bg-red-50 text-red-700 border-red-200',
            
            'selesai'      => 'bg-blue-50 text-blue-700 border-blue-200',
            
            'pending'      => 'bg-gray-50 text-gray-700 border-gray-200',
            'menunggu'     => 'bg-gray-50 text-gray-700 border-gray-200',
            'draft'        => 'bg-gray-50 text-gray-700 border-gray-200',
            
            'mendatang'    => 'bg-orange-50 text-orange-700 border-orange-200',
            
            'berlangsung'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'aktif'        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'disetujui'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ];
        $cls = $styles[$statusDatabase] ?? 'bg-emerald-50 text-emerald-700 border-emerald-200';
    @endphp
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $cls }} capitalize">
        {{ $status ?? '-' }}
    </span>
@endif