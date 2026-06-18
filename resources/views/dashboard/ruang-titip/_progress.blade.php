{{-- Progress bar 4 langkah. Pakai: @include('dashboard.ruang-titip._progress', ['step' => 1]) --}}
@php
    $labels = ['Detail Penitipan', 'Opsi Logistik', 'Alamat', 'Checkout'];
@endphp
<div class="flex items-start gap-0 mb-6 overflow-x-auto pb-1">
    @foreach ($labels as $i => $label)
        <div class="flex items-center flex-1 last:flex-none min-w-0">
            <div class="flex flex-col items-center gap-1.5 shrink-0">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold"
                     style="background:{{ $i < $step - 1 ? '#7c3aed' : ($i === $step - 1 ? 'linear-gradient(135deg,#7c3aed,#6366f1)' : 'rgba(255,255,255,0.08)') }};
                            color:{{ $i <= $step - 1 ? 'white' : 'rgba(255,255,255,0.3)' }};
                            box-shadow:{{ $i === $step - 1 ? '0 0 12px rgba(124,58,237,0.55)' : 'none' }};">
                    @if ($i < $step - 1)<x-lucide-check class="w-3.5 h-3.5" />@else{{ $i + 1 }}@endif
                </div>
                <span class="text-[9px] font-medium text-center leading-tight w-16"
                      style="color:{{ $i <= $step - 1 ? '#a78bfa' : 'rgba(255,255,255,0.25)' }};">{{ $label }}</span>
            </div>
            @if ($i < count($labels) - 1)
                <div class="flex-1 h-px mx-1 mb-5" style="background:{{ $i < $step - 1 ? '#7c3aed' : 'rgba(255,255,255,0.09)' }};"></div>
            @endif
        </div>
    @endforeach
</div>
