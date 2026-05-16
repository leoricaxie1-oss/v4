<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\City;
use App\Models\Purok;
use Illuminate\Http\JsonResponse;

class AddressLookupController extends Controller
{
    public function cities(int $provinceId): JsonResponse
    {
        return response()->json(
            City::where('province_id', $provinceId)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function barangays(int $cityId): JsonResponse
    {
        return response()->json(
            Barangay::where('city_id', $cityId)->orderBy('name')->get(['id', 'name'])
        );
    }

    public function puroks(int $barangayId): JsonResponse
    {
        return response()->json(
            Purok::where('barangay_id', $barangayId)->orderBy('name')->get(['id', 'name'])
        );
    }
}
