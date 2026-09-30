<?php

namespace App\Http\Controllers;

use App\Models\LandingSurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingSurveyController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'minat' => 'required|in:tertarik,mungkin,tidak',
            'layanan' => 'nullable|array',
            'layanan.*' => 'string',
            'harga' => 'nullable|string',
        ]);

        LandingSurveyResponse::create([
            'minat' => $data['minat'],
            'layanan' => $data['layanan'] ?? [],
            'harga' => $data['harga'] ?? null,
            'user_id' => Auth::id(),
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['message' => 'Terima kasih atas jawabanmu!']);
    }
}
