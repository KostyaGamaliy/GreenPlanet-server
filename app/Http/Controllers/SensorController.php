<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\SensorDates;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SensorController extends Controller
{
    public function storeDates(Request $request) {
        if ($request->soil_moisture_data < 50) {
            $plant = Plant::findOrFail($request->sensor_id);
            $plant->update(['watering_time' => Carbon::now()->format('Y-m-d H:i:s')]);
        }

        $iotData = SensorDates::create([
            'sensor_id' => $request->sensor_id,
            'light_data' => $request->light_data,
            'soil_moisture_data' => $request->soil_moisture_data,
        ]);

        return response()->json([
            'sensor_status_id' => $iotData->sensor->id,
            'name' => $iotData->sensor->name,
        ]);
    }
}
