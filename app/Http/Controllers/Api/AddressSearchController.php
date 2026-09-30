<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class AddressSearchController extends Controller
{
    public function search(Request $request, DistanceService $distance)
    {
        $query = (string) $request->query('q', '');

        return response()->json([
            'results' => $distance->suggest($query),
        ]);
    }
}
