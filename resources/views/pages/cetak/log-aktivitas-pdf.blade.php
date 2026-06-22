<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        body        { font-family: sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header     { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #1A2B5C; padding-bottom: 8px; }
        .header h2  { margin: 0 0 3px; color: #1A2B5C; font-size: 14px; text-transform: uppercase; letter-spacing: .5px; }
        .header p   { margin: 0; font-size: 11px; color: #475569; font-weight: bold; }
        .meta       { font-size: 9px; font-style: italic; color: #555; margin-bottom: 10px; line-height: 1.6; }
        table       { width: 100%; border-collapse: collapse; margin-top: 4px; }
        th, td      { border: 1px solid #94a3b8; padding: 5px 7px; text-align: left; vertical-align: top; }
        th          { background-color: #1A2B5C; color: #fff; font-weight: bold; font-size: 9px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .badge      { display: inline-block; padding: 1px 6px; border-radius: 20px; font-size: 8px; font-weight: bold; }
        .badge-green  { background:#dcfce7; color:#166534; }
        .badge-blue   { background:#eff6ff; color:#1e40af; }
        .badge-red    { background:#fee2e2; color:#991b1b; }
        .badge-gray   { background:#f3f4f6; color:#374151; }
        .kategori-pill{ display: inline-block; padding: 1px 6px; border-radius: 20px; font-size: 8px; background:#eff1f8; color:#1A2B5C; }
        .footer     { margin-top: 14px; font-size: 9px; color: #94a3b8; text-align: right; }
        .empty      { text-align: center; color: #94a3b8; font-style: italic; padding: 20px; }
    </style>
</head>
<body>

<div class="header">
    <h2>Sistem Informasi Monitoring Kegiatan Organisasi Mahasiswa (SIMKOM)</h2>
    <p>{{ $judul }}</p>
</div>

<div class="meta">
    Dicetak Oleh: {{ $namaUser }} ({{ $role }})<br>
    Waktu Unduh: {{ now()->translatedFormat('d F Y H:i') }} WITA<br>
    Total Entri: {{ $logs->count() }}
</div>

@php
$kategoriMap = [
    'akun_user'            => 'Akun',
    'manajemen_organisasi' => 'Organisasi',
    'manajemen_kegiatan'   => 'Kegiatan',
    'anggota_organisasi'   => 'Anggota',
    'verifikasi_anggota'   => 'Verifikasi',
    'pendaftaran_kegiatan' => 'Pendaftaran',
    'dokumen_kegiatan'     => 'Dokumen',
    'input_keuangan'       => 'Keuangan',
];
$eventBadge = [
    'created' => ['Tambah',   'badge-green'],
    'updated' => ['Perbarui', 'badge-blue'],
    'deleted' => ['Hapus',    'badge-red'],
];
@endphp

<table>
    <thead>
        <tr>
            <th style="width:15%">Waktu</th>
            @if(!empty($isAdmin))
            <th style="width:16%">Pelaku</th>
            @endif
            <th style="width:10%">Aksi</th>
            <th style="width:12%">Kategori</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
        @php
            [$badgeLabel, $badgeClass] = $eventBadge[$log->event] ?? ['Aksi', 'badge-gray'];
            $kategori = $kategoriMap[$log->log_name] ?? $log->log_name;
        @endphp
        <tr>
            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
            @if(!empty($isAdmin))
            <td>
                {{ optional($log->causer)->name ?? '—' }}
                @if(optional($log->causer)->role)
                    <br><span style="color:#6b7280;font-size:8px;">{{ ucfirst($log->causer->role) }}</span>
                @endif
            </td>
            @endif
            <td><span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
            <td><span class="kategori-pill">{{ $kategori }}</span></td>
            <td>{{ $log->description }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ !empty($isAdmin) ? 5 : 4 }}" class="empty">Belum ada aktivitas yang tercatat.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Dokumen ini digenerate secara otomatis oleh sistem SIMKOM Bali &mdash; {{ now()->format('d/m/Y H:i') }}
</div>

</body>
</html>
