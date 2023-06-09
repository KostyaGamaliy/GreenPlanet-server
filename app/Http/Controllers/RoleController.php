<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index() {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canViewPlants', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $roles = Role::all();

        return response()->json($roles);
    }

    public function store(StoreRoleRequest $request) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canStoreRole', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        return response()->json($role);
    }

    public function getRole($id) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canUpdateRole', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $role = Role::findOrFail($id);

        return response()->json($role);
    }

    public function update($id, UpdateRoleRequest $request) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canUpdateRole', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);

        return response()->json($role);
    }

    public function destroy($id) {
        $isPolicy = Auth::guard('sanctum')->user();

        try {
            $this->authorize('canDestroyRole', $isPolicy);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'message' => 'Роль була видалена'
        ]);
    }
}
