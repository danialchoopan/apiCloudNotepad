<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('JwtCheckToken', ['except' => ['login', 'register','checkToken']]);
    }


    public function register(Request $request)
    {
        $credentials = $request->only('name', 'email', 'password');
        User::create(array_merge($credentials, ['password' => Hash::make($request->password)]));
        return $this->login($request);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response([
                'success' => false,
                'message' => 'Unauthorized'
            ]);
        }
        return response([
            'success' => true,
            'token' => $token,
            'user' => Auth::user(),
            'message' => 'user successfully login in'
        ]);
    }

    public function refresh(Request $request)
    {
        return response([
            'token' => auth()->refresh(),
            'user' => Auth::user(),
            'message' => 'user successfully login in'
        ]);
    }


    public function changePassword(Request $request)
    {
        try {
            if (Hash::check($request->oldPassword, Auth::user()->getAuthPassword())) {
                $newPassword = Hash::make($request->newPassword);
                $user = Auth::user();
                $user->password = $newPassword;
                $user->save();
                return response([
                    'success' => true,
                    'message' => 'change password successfully'
                ]);
            } else {
                return response([
                    'success' => false,
                    'message' => 'old password not match'
                ]);
            }
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }

    public function checkToken()
    {
        return response([
            'success' => true
        ]);
    }

    public function logout()
    {
        auth()->logout();
        return response([
            'success' => true,
            'message' => 'Successfully logged out']);
    }

}
