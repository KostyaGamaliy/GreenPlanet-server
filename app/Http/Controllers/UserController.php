<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index() {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $users = User::all();

        foreach ($users as $user) {
            $user->role = $user->role;
        }

        return response()->json($users);
    }

    public function getUser($id) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $user = User::findOrFail($id);
        $user->role = $user->role;

        return response()->json($user);
    }

    public function getUsersExceptCompany() {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canGetUsersExceptCompany', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $users = User::all();

        $res = [];

        foreach ($users as $user) {
            if ($user->company_id === null && $user->role->id !== 1) {
                $res[] = $user;
            }
        }

        return response()->json($res);
    }

    public function update($id, Request $request) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $user = User::findOrFail($id);
        $requestData = json_decode($request->getContent(), true);

        if ($request->hasFile('user.image')) {
            $image = $request->file('user.image');
            $user->image = $image->store('images', 'public');
        } else {
            $user->image = 'images/default-image-for-user.png';
        }

        $user->full_name = $requestData['user']['full_name'];
        $user->email = $requestData['user']['email'];
        $user->role_id = $requestData['user']['role']['id'];

        $user->update();
    }

    public function addToCompany($userId, $companyId) {
        $user = User::findOrFail($userId);

        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStoreUserToCompany', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $user->update(['company_id' => $companyId]);
        $user->update(['role_id' => 4]);

        return response()->json($user);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canDestroyUser', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        if ($user->image !== 'images/default-image-for-user.png') {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();
    }

    public function removeFromCompany($userId) {
        $user = User::findOrFail($userId);
        $isAdmin = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canRemoveFromCompany', $isAdmin);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для менеджера або адміністратора']);
        }

        $user->company_id = null;
        $user->save();

        return response()->json($user);
    }
}
