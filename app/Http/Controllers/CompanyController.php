<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Jobs\CreateCompanyJob;
use App\Models\Application;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
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

    public function getUsers($id) {
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStoreCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $company = Company::findOrFail($id);
        $users = $company->users;

        foreach ($users as $user) {
            $user->role = $user->role;
        }

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $user = User::find($request->user_id);
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStoreCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        if ($request->isStore) {
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

            $emailData = [
                'recipient' => $user,
                'isStore' => $request->isStore,
                'company' => $company
            ];

            $application = Application::findOrFail($request->application_id);

            $application->delete();
        } else {
            $application = Application::findOrFail($request->application_id);

            $application->delete();

            $emailData = [
                'recipient' => $user,
                'isStore' => $request->isStore,
                'company' => ['name' => $request->name]
            ];
        }

        CreateCompanyJob::dispatch($emailData);

        return response()->json($company ?? 'Не одобрено');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewCompany', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для працівників компанії або адміністратора']);
        }

        $companies = Company::findOrFail($id);

        return response()->json($companies);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request)
    {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canUpdateCompany', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $company = Company::findOrFail($request->id);

        if ($company->image !== "images/default-image-for-company.png") {
            Storage::disk('public')->delete($company->image);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data['image'] = $image->store('images', 'public');
        } else {
            $data['image'] = 'images/default-image-for-company.png';
        }

        $company->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $data['image'],
            'location' => $request->location,
        ]);

        return response()->json($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canDeleteCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $company = Company::findOrFail($id);

        // Установите company_id всех пользователей, связанных с данной компанией, в null
        $company->users()->update(['company_id' => null]);

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
