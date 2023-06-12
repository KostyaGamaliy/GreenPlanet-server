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
            $application->sender = User::findOrFail($application->sender_id);
        }

        return response()->json($applications);
    }

    public function show($id) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewApplications', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $application = Application::findOrFail($id);

        return response()->json($application);
    }

    public function store(Request $request) {
        $localUser = Auth::guard('sanctum')->user();

        $data['sender_id'] = $localUser->id;
        $data['company_name'] = $request->company_name;
        $data['company_description'] = $request->company_description;
        $data['location'] = $request->location;

        $logo = $request->file('logo');
        $document = $request->file('document');

        if ($logo) {
            $data['company_image'] = $logo->store('images', 'public');
        } else if (!$logo) {
            $data['company_image'] = 'images/default-image-for-company.png';
        }

        if ($document) {
            $data['documents'] = $document->store('images', 'public');
        } else if (!$document) {
            $data['documents'] = 'images/default-image-for-documents.png';
        }

        $application = Application::create($data);
    }
}
