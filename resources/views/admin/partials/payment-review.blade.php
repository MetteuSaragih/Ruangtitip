{{--
    Pemeriksaan pembayaran QRIS manual: bukti dari pelanggan, tombol "Pembayaran berhasil" dan "Pembayaran gagal".
    Parameter: $type (titip|preloved|packing), $id, $bill (hasil BillingFeed::normalize).
--}}
@php
    $rpPay = fn ($n) => 'Rp ' . number_format((int) $n, 0, ',', '.');
    $fmtPay = fn ($d) => $d ? $d->copy()->timezone('Asia/Jakarta')->format('d/m/Y H:i') : '-';
    $state = $bill['state'];
@endphp

<div class="card">
    <div class="card-h">
        <h3>Bukti pembayaran</h3>
        <span class="st {{ $bill['tone'] }}">{{ $bill['label'] }}</span>
    </div>

    @if ($bill['proof_url'])
        <a href="{{ $bill['proof_url'] }}" target="_blank" rel="noopener" class="pay-proof" title="Buka ukuran penuh">
            <img src="{{ $bill['proof_url'] }}" alt="Bukti pembayaran {{ $bill['code'] }}" loading="lazy">
        </a>
        <div class="kv" style="margin-top:12px">
            <div><span>Dikirim</span><b>{{ $fmtPay($bill['proof_at']) }}</b></div>
            <div><span>Percobaan unggah</span><b>{{ $bill['attempts'] }}</b></div>
            <div><span>Nominal tagihan</span><b>{{ $rpPay($bill['amount']) }}</b></div>
        </div>
    @else
        <p class="muted">Pelanggan belum mengunggah bukti pembayaran.</p>
    @endif

    @if ($state === 'review')
        <div class="al info" style="margin-top:12px">
            <svg class="ic" aria-hidden="true"><use href="#i-clock"/></svg>
            <div>
                <b>Cocokkan dengan mutasi QRIS</b>
                <p>Pastikan {{ $rpPay($bill['amount']) }} sudah masuk ke rekening QRIS, lalu pilih hasilnya. Pelanggan langsung melihat statusnya.</p>
            </div>
        </div>
        <div class="acts" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:12px">
            <form method="POST" action="{{ route('admin.tagihan.confirm', [$type, $id]) }}">
                @csrf
                <button type="submit" class="btn btn-primary"
                        data-confirm="Tandai pembayaran {{ $bill['code'] }} sebesar {{ $rpPay($bill['amount']) }} sebagai berhasil? Pesanan akan dilanjutkan ke tahap berikutnya."
                        data-confirm-title="Pembayaran berhasil" data-confirm-ok="Ya, berhasil">Pembayaran berhasil</button>
            </form>
            <button type="button" class="btn btn-danger" data-open="dlgReject">Pembayaran gagal</button>
        </div>

        <dialog class="dlg" id="dlgReject" aria-labelledby="dlgRejectT" @if ($errors->has('reason')) data-reopen @endif>
            <form method="POST" action="{{ route('admin.tagihan.reject', [$type, $id]) }}">
                @csrf
                <div class="dh"><h3 id="dlgRejectT">Pembayaran gagal</h3><button class="x" type="button" data-close aria-label="Tutup"><svg class="ic" aria-hidden="true"><use href="#i-x"/></svg></button></div>
                <div class="db">
                    <p class="muted">Pelanggan akan diminta mengunggah ulang bukti pembayaran. Jelaskan apa yang kurang supaya mereka tahu apa yang harus diperbaiki.</p>
                    <div class="field">
                        <label for="rejectReason">Alasan</label>
                        <textarea class="input" id="rejectReason" name="reason" rows="4" maxlength="500" required
                                  placeholder="Contoh: Nominal belum masuk. Mohon kirim ulang bukti transfer yang jelas dan lengkap.">{{ old('reason') }}</textarea>
                        @error('reason')<span class="err-t">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="df">
                    <button class="btn btn-ghost" type="button" data-close>Batal</button>
                    <button class="btn btn-danger" type="submit">Tandai gagal</button>
                </div>
            </form>
        </dialog>
    @elseif ($state === 'rejected')
        <div class="al err" style="margin-top:12px">
            <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
            <div>
                <b>Pembayaran ditandai gagal</b>
                <p>{{ $bill['reason'] }}</p>
                <p>Menunggu pelanggan mengunggah ulang bukti. Ditolak {{ $fmtPay($bill['reviewed_at']) }}@if (! empty($reviewer)) oleh {{ $reviewer }}@endif.</p>
            </div>
        </div>
    @elseif ($state === 'paid')
        <div class="al ok" style="margin-top:12px">
            <svg class="ic" aria-hidden="true"><use href="#i-check"/></svg>
            <div>
                <b>Pembayaran berhasil</b>
                <p>Dikonfirmasi {{ $fmtPay($bill['paid']) }}@if (! empty($reviewer)) oleh {{ $reviewer }}@endif.</p>
            </div>
        </div>
    @endif
</div>
