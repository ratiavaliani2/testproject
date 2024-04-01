<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller {

    public function login(Request $request){

        $userInfo = $request->only('email', 'password');
        $token = Auth::guard()->attempt($userInfo);

        if ($token) {
            $expires = Auth::guard()->factory()->getTTL() * 60;

            return response()->json([
                'status' => true,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => $expires
            ]);
        }

        return response()->json([
            'status' => false,
            'error' => 'Unauthorized'
        ], 401);
    }

    function logout() {
        Auth::guard()->logout();

        response()->json(['status' => true], 200);
    }
}
