{{--
    Panel status pembayaran QRIS manual, dipakai di halaman hasil pembayaran Ruang Titip, Preloved, dan Packing.
    Parameter:
      $order     model pesanan (TitipanOrder, Order, atau PackingOrder)
      $rows      baris ringkasan [[label, nilai], ...]
      $paidText  kalimat saat pembayaran berhasil
      $actions   tombol penutup [[label, url, kelas], ...]
--}}
@php
    use App\Services\ManualPayment;

    $payType = ManualPayment::typeOf($order);
    $state = ManualPayment::state($order->payment_status);
    $amount = (int) $order->total;
    $rpf = fn ($n) => 'Rp' . number_format((int) $n, 0, ',', '.');
    $rows = $rows ?? [];
    $actions = $actions ?? [];
    $wa = 'https://wa.me/6285121091134';
    $uploadUrl = route('payment.proof.store', [$payType, $order->getKey()]);
    $proofUrl = ManualPayment::proofExists($order) ? route('payment.proof.show', [$payType, $order->getKey()]) : null;

    [$tone, $icon, $title, $text] = match ($state) {
        'paid' => ['ok', 'i-check', 'Pembayaran berhasil', $paidText ?? 'Pembayaranmu sudah kami terima.'],
        'review' => ['info', 'i-clock', 'Pembayaran sedang diperiksa', 'Bukti pembayaran sudah kami terima. Admin sedang memeriksanya, biasanya tidak lama. Halaman ini akan menunjukkan hasilnya.'],
        'rejected' => ['err', 'i-x', 'Pembayaran gagal', 'Admin belum bisa memastikan pembayaranmu. Baca alasannya di bawah, lalu foto ulang bukti pembayaranmu dan kirim lagi.'],
        'failed' => ['err', 'i-x', 'Pembayaran gagal', 'Pesanan ini tidak bisa dibayar lagi. Hubungi admin bila kamu sudah membayar.'],
        default => ['warn', 'i-clock', 'Selesaikan pembayaran', 'Scan QRIS di bawah, bayar sesuai nominal, lalu unggah foto bukti pembayarannya.'],
    };
    $canUpload = ManualPayment::canUpload($order);
@endphp

<section class="card state pay-panel" aria-live="polite">
    <div class="r-ic r-{{ $tone }}"><svg class="ic" aria-hidden="true"><use href="#{{ $icon }}"/></svg></div>
    <h3>{{ $title }}</h3>
    <p>{{ $text }}</p>

    {{-- Langkah: bayar, unggah, diperiksa, selesai --}}
    @if (in_array($state, ['unpaid', 'rejected', 'review', 'paid'], true))
        @php $stepNow = match ($state) { 'review' => 3, 'paid' => 4, default => $order->payment_proof ? 2 : 1 }; @endphp
        <ol class="pay-steps" aria-label="Tahap pembayaran">
            @foreach (['Bayar QRIS', 'Unggah bukti', 'Diperiksa admin', 'Berhasil'] as $i => $label)
                @php $n = $i + 1; @endphp
                <li class="{{ $n < $stepNow ? 'done' : ($n === $stepNow ? 'now' : '') }}" @if ($n === $stepNow) aria-current="step" @endif>
                    <span class="n">{{ $n < $stepNow ? '✓' : $n }}</span><span class="t">{{ $label }}</span>
                </li>
            @endforeach
        </ol>
    @endif

    @if ($state === 'rejected' && $order->payment_reject_reason)
        <div class="pay-note err" role="alert">
            <b>Alasan dari admin</b>
            <p>{{ $order->payment_reject_reason }}</p>
        </div>
    @endif

    @if ($canUpload)
        <div class="pay-qris">
            <figure>
                <img src="{{ ManualPayment::qrisUrl() }}" alt="Kode QRIS {{ ManualPayment::merchant() }}" width="260" height="344" loading="lazy">
                <figcaption>{{ ManualPayment::merchant() }}</figcaption>
            </figure>
            <div class="pay-amt">
                <small>Nominal yang harus dibayar</small>
                <b id="payAmount" data-amount="{{ $amount }}">{{ $rpf($amount) }}</b>
                <p>Bayar tepat sesuai nominal supaya mudah dicocokkan admin.</p>
                <div class="pay-qacts">
                    <button type="button" class="btn btn-ghost sm" id="payCopy">Salin nominal</button>
                    <a class="btn btn-ghost sm" href="{{ ManualPayment::qrisUrl() }}" download="qris-ruangtitip">Simpan QRIS</a>
                </div>
            </div>
        </div>

        <form class="pay-form" method="POST" action="{{ $uploadUrl }}" enctype="multipart/form-data" id="payForm" novalidate>
            @csrf
            <label for="payProof">{{ $state === 'rejected' ? 'Unggah ulang bukti pembayaran' : 'Unggah bukti pembayaran' }}</label>
            <input type="file" id="payProof" name="proof" accept="image/jpeg,image/png,image/webp" required aria-describedby="payProofHelp payProofErr">
            <p class="help" id="payProofHelp">Foto atau tangkapan layar bukti bayar. Pastikan nominal, tanggal, dan jam terbaca jelas. JPG, PNG, atau WEBP, maksimal {{ (int) (config('payment.proof.max_kb') / 1024) }} MB.</p>
            <p class="pay-err" id="payProofErr" role="alert">{{ $errors->first('proof') }}</p>
            <img class="pay-prev" id="payPrev" alt="Pratinjau bukti pembayaran" hidden>
            <button type="submit" class="btn btn-sun wide" id="payBtn" disabled>{{ $state === 'rejected' ? 'Kirim ulang bukti' : 'Kirim bukti pembayaran' }}</button>
        </form>
    @endif

    @if ($state === 'review' && $proofUrl)
        <div class="pay-sent">
            <a href="{{ $proofUrl }}" target="_blank" rel="noopener"><img src="{{ $proofUrl }}" alt="Bukti pembayaran yang kamu kirim" loading="lazy"></a>
            <p class="help">Dikirim {{ $order->payment_proof_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H.i') }} WIB. Salah foto? Hubungi admin lewat WhatsApp.</p>
        </div>
    @endif

    <div class="rows pay-rows">
        <div><span>No. pesanan</span><b>{{ ManualPayment::codeOf($order) }}</b></div>
        @foreach ($rows as [$label, $value])
            <div><span>{{ $label }}</span><b>{{ $value }}</b></div>
        @endforeach
        <div class="tot"><span>{{ $state === 'paid' ? 'Total dibayar' : 'Total tagihan' }}</span><b>{{ $rpf($amount) }}</b></div>
        <div><span>Status</span><b>{{ ManualPayment::label($order->payment_status) }}</b></div>
    </div>

    <div class="acts">
        @if ($state === 'failed')
            <a class="btn btn-sun" href="{{ $wa }}" target="_blank" rel="noopener">Hubungi admin</a>
        @endif
        @foreach ($actions as [$label, $url, $class])
            <a class="btn {{ $class }}" href="{{ $url }}">{{ $label }}</a>
        @endforeach
    </div>
</section>

@if ($canUpload)
    @push('scripts')
    <script>
    (function () {
        var input = document.getElementById('payProof');
        var btn = document.getElementById('payBtn');
        var err = document.getElementById('payProofErr');
        var prev = document.getElementById('payPrev');
        var form = document.getElementById('payForm');
        var maxBytes = {{ (int) config('payment.proof.max_kb') }} * 1024;
        var okTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!input || !btn) { return; }

        input.addEventListener('change', function () {
            var f = input.files && input.files[0];
            err.textContent = '';
            prev.hidden = true;
            btn.disabled = true;
            if (!f) { return; }
            if (okTypes.indexOf(f.type) === -1) { err.textContent = 'Bukti harus berupa foto JPG, PNG, atau WEBP.'; input.value = ''; return; }
            if (f.size > maxBytes) { err.textContent = 'Ukuran foto terlalu besar. Maksimal {{ (int) (config('payment.proof.max_kb') / 1024) }} MB.'; input.value = ''; return; }
            prev.src = URL.createObjectURL(f);
            prev.hidden = false;
            btn.disabled = false;
        });

        form.addEventListener('submit', function (e) {
            if (!input.files || !input.files[0]) { e.preventDefault(); err.textContent = 'Pilih foto bukti pembayaran dulu.'; return; }
            btn.disabled = true;
            btn.textContent = 'Mengirim...';
        });

        var copy = document.getElementById('payCopy');
        var amt = document.getElementById('payAmount');
        if (copy && amt) {
            copy.addEventListener('click', function () {
                var v = String(amt.getAttribute('data-amount'));
                var done = function () { copy.textContent = 'Nominal disalin'; setTimeout(function () { copy.textContent = 'Salin nominal'; }, 2000); };
                if (navigator.clipboard && navigator.clipboard.writeText) { navigator.clipboard.writeText(v).then(done, function () {}); }
            });
        }
    })();
    </script>
    @endpush
@endif
