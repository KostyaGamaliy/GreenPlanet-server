<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
//    public function __construct()
//    {
//        $this->authorizeResource(Company::class, 'company');
//    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewCompanies', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $companies = Company::all();

        return response()->json($companies);
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
        $user = User::where('id', $request->user_id);
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStoreCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image->store('images', 'public');
        } else {
            $data['image'] = 'images/default-image-for-company.png';
        }

        $company = Company::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $data['image'],
            'location' => $request->location,
        ]);

        $user->update(['company_id' => $company->id]);

        return response()->json($company);
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
    public function update(UpdateCompanyRequest $request, string $id)
    {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canUpdateCompany', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $company = Company::findOrFail($id);

        $company->update($request);

        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $data = $request->user_id;
        $user = User::find($request->user_id);
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canDeleteCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $company = Company::findOrFail($id);
        $user->company_id = null;
        $user->save();

        if ($company->image !== "images/default-image-for-company.png") {
            Storage::disk('public')->delete($company->image);
        }

        if ($company->plants()->exists()) {
            $company->plants()->delete();
        }

        $company->delete();

        return response()->json([
            'message' => 'Видалення компанії пройшло успішно'
        ]);
    }
}
