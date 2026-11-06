{{--
    Panel status pembayaran QRIS manual, dipakai di halaman pembayaran Ruang Titip, Preloved, dan Packing.
    Tata letak: kepala status + langkah, lalu dua kolom (QRIS di kiri; nominal, unggah bukti, rincian di kanan).
    Parameter:
      $order     model pesanan (TitipanOrder, Order, atau PackingOrder)
      $rows      baris rincian [[label, nilai], ...]
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
    $uploadUrl = route('payment.proof.store', [$payType, $order->getKey()]);
    $proofUrl = ManualPayment::proofExists($order) ? route('payment.proof.show', [$payType, $order->getKey()]) : null;

    [$tone, $icon, $title, $text] = match ($state) {
        'paid' => ['ok', 'i-check', 'Pembayaran berhasil', $paidText ?? 'Pembayaranmu sudah kami terima.'],
        'review' => ['info', 'i-clock', 'Pembayaran sedang diperiksa', 'Bukti pembayaranmu sudah kami terima. Admin sedang memeriksanya, biasanya tidak lama. Halaman ini menunjukkan hasilnya.'],
        'rejected' => ['err', 'i-x', 'Pembayaran gagal', 'Admin belum bisa memastikan pembayaranmu. Baca alasannya, lalu foto ulang bukti pembayaranmu dan kirim lagi.'],
        'failed' => ['err', 'i-x', 'Pembayaran gagal', 'Pesanan ini tidak bisa dibayar lagi. Hubungi admin bila kamu sudah membayar.'],
        default => ['warn', 'i-clock', 'Selesaikan pembayaran', 'Scan QRIS, bayar sesuai nominal, lalu unggah foto bukti pembayarannya.'],
    };
    $canUpload = ManualPayment::canUpload($order);
    $qrisUrl = ManualPayment::qrisUrl();
    $stepNow = match ($state) { 'review' => 3, 'paid' => 4, default => $order->payment_proof ? 2 : 1 };
    $maxMb = (int) (config('payment.proof.max_kb') / 1024);
    $sentAt = $order->payment_proof_at?->timezone('Asia/Jakarta')->locale('id')->translatedFormat('j F Y, H.i');
@endphp

<section class="pay" aria-live="polite">
    {{-- Kepala: status dan penjelasan --}}
    <header class="pay-head">
        <span class="pay-ic pay-ic-{{ $tone }}" aria-hidden="true"><svg class="ic"><use href="#{{ $icon }}"/></svg></span>
        <div class="pay-head-t">
            <h3>{{ $title }}</h3>
            <p>{{ $text }}</p>
        </div>
        <span class="pay-st pay-st-{{ $tone }}">{{ ManualPayment::label($order->payment_status) }}</span>
    </header>

    {{-- Langkah --}}
    @if (in_array($state, ['unpaid', 'rejected', 'review', 'paid'], true))
        <ol class="pay-steps" aria-label="Tahap pembayaran">
            @foreach (['Bayar QRIS', 'Unggah bukti', 'Diperiksa admin', 'Berhasil'] as $i => $label)
                @php $n = $i + 1; @endphp
                <li class="{{ $n < $stepNow ? 'done' : ($n === $stepNow ? 'now' : '') }}" @if ($n === $stepNow) aria-current="step" @endif>
                    <span class="n">@if ($n < $stepNow)<svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>@else{{ $n }}@endif</span><span class="t">{{ $label }}</span>
                </li>
            @endforeach
        </ol>
    @endif

    @if ($state === 'rejected' && $order->payment_reject_reason)
        <div class="pay-note" role="alert">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div><b>Alasan dari admin</b><p>{{ $order->payment_reject_reason }}</p></div>
        </div>
    @endif

    <div class="pay-grid {{ $canUpload ? '' : 'pay-grid-one' }}">
        {{-- Kiri: QRIS --}}
        @if ($canUpload)
            <aside class="pay-qr" aria-label="Kode QRIS">
                @if ($qrisUrl)
                    <img src="{{ $qrisUrl }}" alt="Kode QRIS {{ ManualPayment::merchant() }}" width="300" height="425">
                    <div class="pay-qr-foot">
                        <b>{{ ManualPayment::merchant() }}</b>
                        <a class="pay-link" href="{{ $qrisUrl }}" download="qris-ruangtitip">Simpan QRIS</a>
                    </div>
                @else
                    <div class="pay-note" role="alert">
                        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
                        <div><b>Kode QRIS belum tersedia</b><p>Hubungi admin lewat WhatsApp untuk mendapatkan kode pembayaran.</p></div>
                    </div>
                @endif
            </aside>
        @endif

        {{-- Kanan: nominal, unggah bukti, rincian --}}
        <div class="pay-main">
            <div class="pay-total">
                <div>
                    <small>{{ $state === 'paid' ? 'Total dibayar' : 'Total yang harus dibayar' }}</small>
                    <b id="payAmount" data-amount="{{ $amount }}">{{ $rpf($amount) }}</b>
                    <span class="pay-code">No. pesanan {{ ManualPayment::codeOf($order) }}</span>
                </div>
                @if ($canUpload)
                    <button type="button" class="btn btn-ghost sm" id="payCopy">Salin nominal</button>
                @endif
            </div>
            @if ($canUpload)
                <p class="pay-hint">Bayar tepat sesuai nominal supaya mudah dicocokkan admin.</p>
            @endif

            @if ($canUpload)
                <form class="pay-form" method="POST" action="{{ $uploadUrl }}" enctype="multipart/form-data" id="payForm" novalidate>
                    @csrf
                    <span class="pay-lab" id="payLab">{{ $state === 'rejected' ? 'Unggah ulang bukti pembayaran' : 'Unggah bukti pembayaran' }}</span>

                    <input class="pay-file" type="file" id="payProof" name="proof" accept="image/jpeg,image/png,image/webp" required aria-labelledby="payLab" aria-describedby="payProofHelp payProofErr">
                    <label class="pay-drop" for="payProof" id="payDrop">
                        <img class="pay-thumb" id="payPrev" alt="Pratinjau bukti pembayaran" hidden>
                        <span class="pay-drop-ic" id="payDropIc" aria-hidden="true"><svg class="ic"><use href="#i-cam"/></svg></span>
                        <span class="pay-drop-t">
                            <b id="payName">Pilih foto bukti pembayaran</b>
                            <small id="payProofHelp">JPG, PNG, atau WEBP, maksimal {{ $maxMb }} MB. Pastikan nominal, tanggal, dan jam terbaca jelas.</small>
                        </span>
                    </label>
                    <p class="pay-err" id="payProofErr" role="alert">{{ $errors->first('proof') }}</p>

                    <button type="submit" class="btn btn-sun wide" id="payBtn" disabled>{{ $state === 'rejected' ? 'Kirim ulang bukti' : 'Kirim bukti pembayaran' }}</button>
                </form>
            @endif

            @if ($state === 'review' && $proofUrl)
                <div class="pay-sent">
                    <a href="{{ $proofUrl }}" target="_blank" rel="noopener" title="Buka ukuran penuh"><img src="{{ $proofUrl }}" alt="Bukti pembayaran yang kamu kirim" loading="lazy"></a>
                    <div>
                        <b>Bukti terkirim</b>
                        <p>{{ $sentAt }} WIB. Salah foto? Hubungi admin lewat WhatsApp.</p>
                    </div>
                </div>
            @endif

            {{-- Rincian pesanan --}}
            <details class="pay-detail" @if (! $canUpload) open @endif>
                <summary>Rincian pesanan</summary>
                <dl>
                    @foreach ($rows as [$label, $value])
                        <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                    @endforeach
                    <div class="tot"><dt>{{ $state === 'paid' ? 'Total dibayar' : 'Total tagihan' }}</dt><dd>{{ $rpf($amount) }}</dd></div>
                </dl>
            </details>
        </div>
    </div>

    {{-- Tombol penutup --}}
    @if ($state === 'failed' || $actions)
        <div class="pay-acts">
            @if ($state === 'failed')
                <a class="btn btn-sun" href="https://wa.me/6285121091134" target="_blank" rel="noopener">Hubungi admin</a>
            @endif
            @foreach ($actions as [$label, $url, $class])
                <a class="btn {{ $class }}" href="{{ $url }}">{{ $label }}</a>
            @endforeach
        </div>
    @endif
</section>

@if ($canUpload)
    @push('scripts')
    <script>
    (function () {
        var input = document.getElementById('payProof');
        var btn = document.getElementById('payBtn');
        var err = document.getElementById('payProofErr');
        var prev = document.getElementById('payPrev');
        var icon = document.getElementById('payDropIc');
        var name = document.getElementById('payName');
        var drop = document.getElementById('payDrop');
        var form = document.getElementById('payForm');
        var maxBytes = {{ (int) config('payment.proof.max_kb') }} * 1024;
        var okTypes = ['image/jpeg', 'image/png', 'image/webp'];
        var idleText = 'Pilih foto bukti pembayaran';
        if (!input || !btn) { return; }

        function reset() {
            btn.disabled = true;
            prev.hidden = true;
            icon.hidden = false;
            name.textContent = idleText;
            drop.classList.remove('has-file');
        }

        input.addEventListener('change', function () {
            var f = input.files && input.files[0];
            err.textContent = '';
            reset();
            if (!f) { return; }
            if (okTypes.indexOf(f.type) === -1) { err.textContent = 'Bukti harus berupa foto JPG, PNG, atau WEBP.'; input.value = ''; return; }
            if (f.size > maxBytes) { err.textContent = 'Ukuran foto terlalu besar. Maksimal {{ $maxMb }} MB.'; input.value = ''; return; }
            prev.src = URL.createObjectURL(f);
            prev.hidden = false;
            icon.hidden = true;
            name.textContent = f.name;
            drop.classList.add('has-file');
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
