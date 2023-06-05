<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlantRequest;
use App\Models\Company;
use App\Models\Plant;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlantController extends Controller
{
    public function index()
    {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для працівників компанії або адміністратора']);
        }

        $plants = Plant::all();

        return response()->json($plants);
    }

    public function store(Request $request) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStorePlant', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для працівників компанії або адміністратора']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image->store('images', 'public');
        } else {
            $data['image'] = 'images/default-image-for-plant.png';
        }

        DB::transaction(function () use ($request, $data) {
            // Создаем Plant
            $plant = Plant::create([
                'name' => $request->input('name'),
                'image' => $data['image'],
                'watering_time' => $request->input('watering_time'),
                'company_id' => $request->input('company_id'),
            ]);

            // Создаем Sensor и связываем с Plant
            $sensor = Sensor::create([
                'name' => $request->input('sensor_name'),
                'plant_id' => $plant->id,
            ]);

            // Устанавливаем связь в обоих моделях
            $plant->sensor_id = $sensor->id;
            $plant->save();
            $sensor->save();
        });

        return response()->json([
            'message' => 'Рослину і сенсор успішно створено',
        ]);
    }

    public function destroy($id) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canDeletePlant', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $plant = Plant::findOrFail($id);
        $plant->sensor()->delete();
        $plant->delete();

        return response()->json([
            'message' => 'Видалення пройшло успішно'
        ]);
    }
}