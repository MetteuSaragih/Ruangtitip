<?php

namespace App\Http\Controllers; // <-- INI BARIS YANG KURANG SEBELUMNYA

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();

        // Simulasi data alamat (Ganti dengan query DB asli Anda jika sudah ada tabelnya)
        $addresses = [
            ['id' => 1, 'label' => 'Kos Utama', 'address' => 'Jl. Veteran No. 10, Kec. Lowokwaru, Malang 65145', 'isPrimary' => true],
            ['id' => 2, 'label' => 'Kampus', 'address' => 'Jl. MT. Haryono No. 165, Ketawanggede, Malang 65145', 'isPrimary' => false],
        ];

        // Menentukan tab default berdasarkan query parameter (?tab=bantuan)
        $currentTab = $request->query('tab', 'profil');

        return view('profile.index', compact('user', 'addresses', 'currentTab'));
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'whatsapp' => 'required|string|max:20',
        ]);

        // Logika update ke database (Contoh: Auth::user()->update([...]))
        
        return response()->json(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
    }
}