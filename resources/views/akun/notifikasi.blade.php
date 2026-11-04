@extends('akun.layout')

@section('title', 'Notifikasi')
@section('key', 'notifikasi')
@section('sub', 'Pilih kabar yang ingin kamu terima dan lewat mana.')

@section('body')
@unless ($user->phone)
    <div class="alert a-warn">
        <svg class="ic" aria-hidden="true"><use href="#i-warn"/></svg>
        <div>
            <b>WhatsApp belum diisi</b>
            <p>Notifikasi WhatsApp baru terkirim setelah nomormu diisi di profil.</p>
            <div class="acts"><a class="btn btn-ghost sm" href="{{ route('akun.profil') }}">Isi sekarang</a></div>
        </div>
    </div>
@endunless

<form method="POST" action="{{ route('akun.notifikasi.save') }}">
    @csrf
    <section class="card">
        <div style="overflow-x:auto">
            <table class="tbl">
                <thead>
                    <tr><th scope="col">Jenis kabar</th><th scope="col">Website</th><th scope="col">Email</th><th scope="col">WhatsApp</th></tr>
                </thead>
                <tbody>
                    @foreach ($categories as $key => [$label, $desc])
                        <tr>
                            <th scope="row" style="font-weight:600;color:var(--text);font-size:14.5px">{{ $label }}<small>{{ $desc }}</small></th>
                            <td><label class="sw"><input type="checkbox" role="switch" checked disabled aria-label="{{ $label }} lewat website, selalu aktif"><span></span></label></td>
                            @foreach (['email' => 'email', 'wa' => 'WhatsApp'] as $ch => $chLabel)
                                <td><label class="sw"><input type="checkbox" role="switch" name="n[{{ $key }}][{{ $ch }}]" value="1" @checked($prefs[$key][$ch] ?? ($ch === 'email' && $key !== 'promo')) aria-label="{{ $label }} lewat {{ $chLabel }}"><span></span></label></td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="help mt">Notifikasi website selalu aktif supaya kamu tidak melewatkan batas pembayaran.</p>
    </section>

    <div class="savebar" role="region" aria-label="Simpan perubahan" style="margin-top:20px">
        <span>Pengaturan baru berlaku setelah disimpan.</span>
        <button class="btn btn-sun sm" type="submit">Simpan pengaturan</button>
    </div>
</form>
@endsection
