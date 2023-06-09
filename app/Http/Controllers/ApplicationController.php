<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index() {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewApplications', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $applications = Application::all();


        foreach ($applications as $application) {
            $sender = User::findOrFail($application->sender_id);
            $application['sender']['full_name'] = $sender->full_name;
        }

        return response()->json($applications);
    }

    public function create() {

    }

    public function store() {

    }
}
