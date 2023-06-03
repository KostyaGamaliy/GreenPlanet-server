<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::all();
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }
        return response()->json($plants);
    }

    public function store() {

    }
}
