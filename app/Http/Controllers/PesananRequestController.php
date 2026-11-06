<?php

namespace App\Http\Controllers;

use App\Models\TitipanOrder;
use App\Models\TitipanRequest;
use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Permintaan pengguna atas titipan yang sudah di gudang: tambah periode,
 * kirim barang, ambil di gudang. Disimpan sebagai permintaan untuk
 * ditindaklanjuti admin (belum ada tagihan otomatis).
 */
class PesananRequestController extends Controller
{
    public function perpanjang(Request $r, TitipanOrder $order): RedirectResponse
    {
        $this->guard($order, 'perpanjang');

        $data = $r->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $base = $order->date_end && $order->date_end->isFuture() ? $order->date_end->copy() : now();
        $newEnd = $base->addDays((int) $data['days'])->toDateString();

        return $this->store($order, 'perpanjang', [
            'days' => (int) $data['days'],
            'new_date_end' => $newEnd,
        ], $data['catatan'] ?? null, 'Permintaan tambah periode sudah dikirim. Admin akan mengonfirmasi dan menerbitkan tagihan.');
    }

    public function kirim(Request $r, TitipanOrder $order): RedirectResponse
    {
        $this->guard($order, 'kirim');

        $data = $r->validate([
            'address_id' => ['required', 'integer'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $address = UserAddress::where('id', $data['address_id'])->where('user_id', Auth::id())->first();
        abort_unless($address, 403);

        return $this->store($order, 'kirim', [
            'address_id' => $address->id,
            'label' => $address->label,
            'address' => $address->address,
            'area_id' => $address->area_id,
        ], $data['catatan'] ?? null, 'Permintaan kirim barang sudah dikirim. Admin akan menghitung ongkir dan menerbitkan tagihan.');
    }

    public function ambil(Request $r, TitipanOrder $order): RedirectResponse
    {
        $this->guard($order, 'ambil');

        $data = $r->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'method' => ['required', 'in:sendiri,diwakilkan'],
            'rep_name' => ['nullable', 'required_if:method,diwakilkan', 'string', 'max:100'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        return $this->store($order, 'ambil', [
            'date' => $data['date'],
            'method' => $data['method'],
            'rep_name' => $data['method'] === 'diwakilkan' ? ($data['rep_name'] ?? null) : null,
        ], $data['catatan'] ?? null, 'Permintaan pengambilan sudah dikirim. Admin akan mengonfirmasi jadwalnya.');
    }

    private function guard(TitipanOrder $order, string $type): void
    {
        abort_unless($order->user_id === Auth::id(), 403);
        abort_unless($order->status === 'dalam_gudang', 403);
    }

    private function store(TitipanOrder $order, string $type, array $payload, ?string $catatan, string $msg): RedirectResponse
    {
        $exists = $order->requests()->where('type', $type)->whereIn('status', ['menunggu', 'diproses'])->exists();
        if ($exists) {
            return back()->with('error', 'Permintaan ini masih menunggu. Tunggu konfirmasi admin dulu.');
        }

        TitipanRequest::create([
            'titipan_order_id' => $order->id,
            'user_id' => Auth::id(),
            'type' => $type,
            'payload' => $payload,
            'status' => 'menunggu',
            'catatan' => $catatan,
        ]);

        return back()->with('success', $msg);
    }
}
