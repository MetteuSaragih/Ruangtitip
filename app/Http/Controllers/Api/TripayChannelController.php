<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TripayService;

class TripayChannelController extends Controller
{
    public function index(TripayService $tripay)
    {
        return response()->json([
            'channels' => $tripay->getPaymentChannels(),
        ]);
    }
}
