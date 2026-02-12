<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(!$token=JWTAuth::attempt($credentials)){
            return response()->json(['error'=>'Unauthorized'],401);
        }

        return response()->json([
            'token'=>$token,
            'user'=>auth()->user()
        ]);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
