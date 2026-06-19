<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $addresses = UserAddress::where('user_id', $user->id)
            ->orderByDesc('is_primary')
            ->orderByDesc('id')
            ->get()
            ->map(fn (UserAddress $address) => [
                'id' => $address->id,
                'label' => $address->label,
                'address' => $address->address,
                'isPrimary' => $address->is_primary,
            ])
            ->values();

        $currentTab = $request->query('tab', 'profil');

        return view('profile.index', compact('user', 'addresses', 'currentTab'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp' => 'nullable|string|max:20',
        ]);

        return response()->json(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
    }
}
