<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->error('User email or password does not match', 401);
        }else{
            return $this->respondWithToken($token);
        }
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->success([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => $this->getAuthUser(),
        ]);
    }

    protected function getAuthUser()
    {
        return Auth::user();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json(['message' => 'LOGGEDOUT'], 200);
    }
}
