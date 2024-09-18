<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {
            // validate incoming request
            $this->validate($request, [
                'name'     => 'required|string',
                'email'    => 'required|email',
                'password' => 'required|min:6',
            ]);
    
            // Create user
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));
            $user->save();
    
            return response()->success(['user' => $user, 'message' => 'Created Successfully']);
    
        } catch (ValidationException $e) {
            return response()->error(['message' => $e->errors(), 'type'=> 'validation']);
            // return response()->error(['message' => 'your message', 'type'=> 'custom']);
        } catch (\Exception $e) {
            return response()->error(['message' => $e->getMessage(), 'type'=> 'exception']);
            // return response()->exception($e->getMessage(), 409);
        }
    }


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
            try {
                return $this->respondWithToken($token);
            } catch (\Exception $e) {
                return response()->error(['message' => $e->getMessage()], 409);
            }
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
        try {
            Auth::logout();
            return response()->json(['message' => 'LOGGEDOUT'], 200);
        } catch (\Exception $e) {
            return response()->error(['message' => $e->getMessage()], 409);
        }
       
    }
}
