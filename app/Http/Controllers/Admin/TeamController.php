<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class TeamController extends Controller
{
    /** Nilai role pengguna biasa (default kolom role). */
    private const DEFAULT_ROLE = 'penitip';

    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();

        return view('admin.tim', [
            'admins' => $admins,
            'hasLastLogin' => Schema::hasColumn('users', 'last_login_at'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email belum terdaftar. Minta orangnya mendaftar dulu.',
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();
        if ($user->role === 'admin') {
            return back()->withInput()->with('error', 'Pengguna ini sudah menjadi admin.');
        }

        $user->forceFill(['role' => 'admin', 'is_active' => true])->save();

        return back()->with('success', $user->name . ' sekarang menjadi admin.');
    }

    public function revoke(User $user): RedirectResponse
    {
        if ($user->role !== 'admin') {
            return back()->with('error', 'Pengguna ini bukan admin.');
        }
        if ($user->is(Auth::user())) {
            return back()->with('error', 'Kamu tidak bisa mencabut akses admin akunmu sendiri.');
        }
        if (User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Admin terakhir tidak bisa dicabut.');
        }

        $user->forceFill(['role' => self::DEFAULT_ROLE])->save();

        return back()->with('success', 'Akses admin ' . $user->name . ' dicabut.');
    }
}
