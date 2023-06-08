<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\LoginRequest;
    use App\Http\Requests\RegisterRequest;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;

    class AuthController extends Controller
    {
        public function login(LoginRequest $request)
        {
            $credentials = request(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return response()->json([
                    'message' => 'The given data was invalid',
                    'errors' => [
                        'password' => [
                            'Invalid credentials'
                        ]
                    ]
                ], 422);
            }

            $user = User::where('email', $request->email)->first();
            $authToken = $user->createToken('auth-token')->plainTextToken;
            $user->update(['login_token' => $authToken]);

            return response()->json([
                'user' => $user,
                'access_token' => $authToken
            ]);
        }

        public function register(RegisterRequest $request)
        {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data['image'] = $image->store('images', 'public');
            } else {
                $data['image'] = 'images/default-image-for-user.png';
            }

            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'role_id' => $request->role_id,
                'image' => $data['image'],
                'password' => bcrypt($request->password)
            ]);

            return response()->json($user);
        }

        public function logout(Request $request)
        {
            $user = Auth::guard('sanctum')->user();

            if ($user) {
                $user->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();
                $user->login_token = null;
                $user->save();
            }

            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        }
    }
