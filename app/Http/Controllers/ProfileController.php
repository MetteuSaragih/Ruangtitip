<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use App\Services\DistanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct(private DistanceService $distance)
    {
    }

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
                'areaId' => $address->area_id,
                'areaName' => $address->area_name,
                'postalCode' => $address->postal_code,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
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
<<<<<<< HEAD
            'label'   => 'nullable|string|max:50',
            'address' => 'required|string|max:500',
=======
            'label'       => 'nullable|string|max:50',
            'address'     => 'required|string|max:500',
            'area_id'     => 'required|string',
            'area_name'   => 'nullable|string',
            'postal_code' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ], [
            'area_id.required' => 'Pilih kecamatan/kota dari daftar saran.',
>>>>>>> hostinger/main
        ]);

        $user = Auth::user();
        $isPrimary = UserAddress::where('user_id', $user->id)->count() === 0;

        $addr = UserAddress::create([
<<<<<<< HEAD
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
=======
            'user_id'     => $user->id,
            'label'       => $data['label'] ?: 'Alamat Baru',
            'address'     => $data['address'],
            'area_id'     => $data['area_id'],
            'area_name'   => $data['area_name'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'latitude'    => $data['latitude'] ?? null,
            'longitude'   => $data['longitude'] ?? null,
            'is_primary'  => $isPrimary,
        ]);

        $addr = $this->distance->ensureAddressCoords($addr);

        return response()->json([
            'success'    => true,
            'id'         => $addr->id,
            'label'      => $addr->label,
            'address'    => $addr->address,
            'areaId'     => $addr->area_id,
            'areaName'   => $addr->area_name,
            'postalCode' => $addr->postal_code,
            'latitude'   => $addr->latitude,
            'longitude'  => $addr->longitude,
            'isPrimary'  => $addr->is_primary,
        ]);
    }

    public function updateAddress(Request $request, $id)
    {
        $user = Auth::user();
        $addr = UserAddress::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        $data = $request->validate([
            'label'       => 'nullable|string|max:50',
            'address'     => 'required|string|max:500',
            'area_id'     => 'required|string',
            'area_name'   => 'nullable|string',
            'postal_code' => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ], [
            'area_id.required' => 'Pilih kecamatan/kota dari daftar saran.',
        ]);

        $addr->update([
            'label'       => $data['label'] ?: 'Alamat',
            'address'     => $data['address'],
            'area_id'     => $data['area_id'],
            'area_name'   => $data['area_name'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'latitude'    => $data['latitude'] ?? null,
            'longitude'   => $data['longitude'] ?? null,
        ]);

        $addr = $this->distance->ensureAddressCoords($addr);

        return response()->json([
            'success'    => true,
            'id'         => $addr->id,
            'label'      => $addr->label,
            'address'    => $addr->address,
            'areaId'     => $addr->area_id,
            'areaName'   => $addr->area_name,
            'postalCode' => $addr->postal_code,
            'latitude'   => $addr->latitude,
            'longitude'  => $addr->longitude,
            'isPrimary'  => $addr->is_primary,
>>>>>>> hostinger/main
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
