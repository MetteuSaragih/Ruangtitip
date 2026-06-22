<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use Illuminate\Http\Request;

class BiteshipAreaController extends Controller
{
    public function search(Request $request, BiteshipService $biteship)
    {
        $query = (string) $request->query('q', '');

        return response()->json([
            'areas' => $biteship->searchAreas($query),
        ]);
    }
}
