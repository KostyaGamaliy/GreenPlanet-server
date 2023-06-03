<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function destroyUser($id, Request $request) {
        $user = User::findOrFail($id);

        try {
            $this->authorize('canDestroyUser', $user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ця дія можлива лише для адміністрації']);
        }

        if ($user->image !== 'images/default-image-for-user.png') {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return response()->json([
           'message' => $request->message,
        ]);
    }
}