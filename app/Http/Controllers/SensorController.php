<?php

namespace App\Http\Controllers;

use App\Models\SensorDates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensorController extends Controller
{
    public function storeDates(Request $request) {
        $iotData = SensorDates::create([
            'sensor_id' => $request->sensor_id,
            'light_data' => $request->light_data,
            'soil_moisture_data' => $request->soil_moisture_data,
        ]);

        return response()->json($iotData);
    }
}
