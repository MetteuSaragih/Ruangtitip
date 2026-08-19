<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $redirectAfter = session('profile_redirect_after');

        return view('profile.index', compact('user', 'addresses', 'currentTab', 'redirectAfter'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
        ]);

        Auth::user()->update([
            'name' => $data['name'],
            'phone' => $data['whatsapp'] ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Perubahan berhasil disimpan!']);
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,jpg,png,webp|max:3072',
        ]);

        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        return response()->json([
            'success'    => true,
            'avatar_url' => Storage::url($path),
        ]);
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label'   => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
        ]);

        $user = Auth::user();
        $isPrimary = UserAddress::where('user_id', $user->id)->count() === 0;

        $addr = UserAddress::create([
            'user_id'    => $user->id,
            'label'      => $data['label'] ?: 'Alamat Baru',
            'address'    => $data['address'],
            'is_primary' => $isPrimary,
        ]);

        return response()->json([
            'success'   => true,
            'id'        => $addr->id,
            'label'     => $addr->label,
            'address'   => $addr->address,
            'isPrimary' => $addr->is_primary,
        ]);
    }

    public function destroyAddress($id)
    {
        $user = Auth::user();
        $addr = UserAddress::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $wasPrimary = $addr->is_primary;
        $addr->delete();

        if ($wasPrimary) {
            UserAddress::where('user_id', $user->id)->latest()->first()?->update(['is_primary' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function setPrimaryAddress($id)
    {
        $user = Auth::user();
        UserAddress::where('user_id', $user->id)->update(['is_primary' => false]);
        UserAddress::where('id', $id)->where('user_id', $user->id)->update(['is_primary' => true]);

        return response()->json(['success' => true]);
    }

    public function dismissCompleteBanner(Request $request)
    {
        $request->session()->put('profile_banner_dismissed', true);

        return response()->json(['success' => true]);
    }
}
