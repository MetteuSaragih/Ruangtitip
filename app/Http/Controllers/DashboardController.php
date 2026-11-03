<?php

namespace App\Http\Controllers;

use App\Models\PrelovedItem;
use App\Models\TitipanOrder;
use App\Support\UserNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $preloved = PrelovedItem::where('status', 'Tersedia')->latest()->take(3)->get();
        $prelovedCount = PrelovedItem::where('status', 'Tersedia')->count();

        $notifications = UserNotifications::for($user);
        $needs = UserNotifications::needs($user);

        // Titipan yang sedang di gudang, urut dari yang paling cepat berakhir.
        $warehouseOrders = $user
            ? TitipanOrder::with('storage')
                ->where('user_id', $user->id)
                ->where('status', 'dalam_gudang')
                ->orderBy('date_end')
                ->take(5)
                ->get()
            : collect();

        $activeOrder = $warehouseOrders->first();
        $warehouseCount = $user
            ? TitipanOrder::where('user_id', $user->id)->where('status', 'dalam_gudang')->count()
            : 0;
        $hasOrders = $user ? TitipanOrder::where('user_id', $user->id)->exists() : false;

        // Kelengkapan profil: akun, nama lengkap, nomor WhatsApp.
        $profileSteps = $user ? [
            ['Buat akun', true, route('profile.index')],
            ['Isi nama lengkap', mb_strlen(trim((string) $user->name)) >= 3, route('profile.index')],
            ['Isi nomor WhatsApp', ! empty($user->phone), route('profile.index')],
        ] : [];
        $profileDone = collect($profileSteps)->where(1, true)->count();
        $profileNext = collect($profileSteps)->first(fn ($s) => ! $s[1]);

        return view('dashboard.index', compact(
            'user', 'preloved', 'prelovedCount', 'notifications', 'needs', 'warehouseOrders',
            'activeOrder', 'warehouseCount', 'hasOrders', 'profileSteps', 'profileDone', 'profileNext'
        ));
    }

    /** Halaman Notifikasi (sama dengan lonceng di topbar). */
    public function notifications()
    {
        $notifications = UserNotifications::for(Auth::user(), 30);

        return view('notifikasi.index', compact('notifications'));
    }

    public function markNotificationsRead(Request $request)
    {
        $request->session()->put('notif_read_at', now()->toIso8601String());

        return back();
    }
}
