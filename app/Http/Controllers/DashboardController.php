<?php

namespace App\Http\Controllers;

use App\Models\PackingProduct;
use App\Models\PrelovedItem;
use App\Models\StorageRoom;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $storages = StorageRoom::active()->latest()->take(3)->get();
        $packing = PackingProduct::active()->latest()->take(4)->get();
        $preloved = PrelovedItem::where('status', 'Tersedia')->latest()->take(4)->get();
        $testimonials = collect();

        return view('dashboard.index', compact('user', 'storages', 'packing', 'preloved', 'testimonials'));
    }
}
