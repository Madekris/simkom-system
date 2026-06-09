{{--
    KOMPONEN: card.blade.php
    Props: $class (string, opsional) — class tambahan
    Contoh:
    @component('components.card', ['class' => 'p-6'])
        Konten card
    @endcomponent

    Atau pakai include biasa:
    <div @include('components.card-div') > ... </div>  — TIDAK DISARANKAN
    Gunakan sebagai Blade Component (Laravel 7+):
    <x-card class="p-6"> ... </x-card>
--}}
<div {{ $attributes->merge(['class' =>
    'bg-white rounded-xl border border-[#E5E7EB] shadow-[0_2px_12px_rgba(0,0,0,0.04)] ' .
    ($class ?? '')
]) }}>
    {{ $slot }}
</div>
