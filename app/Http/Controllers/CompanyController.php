<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $user = Auth::user();

        try {
            $this->authorize('canStoreCompany', $user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image->store('images', 'public');
        } else {
            $data['image'] = 'images/default-image-for-company.png';
        }

        Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $data['image'],
            'location' => $request->location,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
