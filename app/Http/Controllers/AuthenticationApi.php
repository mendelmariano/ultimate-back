<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthenticationApi extends Controller
{


    //

    public function login(Request $request){


        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(! $user){
            return response()->json(["message" => "Email incorreto"]);
        }

        if(! Hash::check($request->password, $user->password)){
            return response()->json(["message" => "Password incorreto!"]);

        }

        //$token = $user->createToken($request->email.strtotime("now"))->plainTextToken;
        //dd($request);
        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);


        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer',
            "user" => $user,
            //'expires_in' => auth()->factory()->getTTL() * 60
        ]);

    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user','token'),201);
    }

    public function destroy(User $user)
    {
        //
        try {
            $user->delete();

            return response()->json([
                    'message' => 'Usuario excluido com sucesso!'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir usuário."
            ], 500);
        }
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();
        return response()->json(["message" => "Logout"], 201);
    }

    public function loadUserWithClubs(Request $request){
        return $request->user()->load("clubs");
    }

    public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }
}
