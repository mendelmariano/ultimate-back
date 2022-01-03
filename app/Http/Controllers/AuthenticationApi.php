<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(["message" => "Email incorreto"],401);
        }

        if(! Hash::check($request->password, $user->password)){
            return response()->json(["message" => "Password incorreto!"], 401);

        }

        if($user->active==0){
            return response()->json(["message" => "Entre em contato com um ADM e solicite a liberação do seu cadastro."], 402);
        }

        if($user->active==2){
            return response()->json(["message" => "Você foi banido!!! Entre em contato com um ADM e solicite a liberação do seu cadastro."], 402);
        }

        $menusFormatoArray = [];
        $menus=explode(",", $user->menuIds);

        $userFormatado = $user;

        unset($userFormatado->menuIds);
        $userFormatado->menuIds = $menus;

        //dd($userFormatado);
        //$token = $user->createToken($request->email.strtotime("now"))->plainTextToken;
        //dd($request);
        $credentials = $request->only('email', 'password');

        $token = JWTAuth::attempt($credentials);


        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer',
            "user" => $userFormatado,
            //'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);

    }


    public function registerClub(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'clubName' => 'required|string|max:255',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 401);
        }

        //dd($request->all());


        $user = Club::create([
            'name' => $request->get('clubName'),
            'user_id' => $request->get('user_id'),
        ]);
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'whatsapp' => 'required|string|max:255|unique:users',
        ]);

        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 401);
        }

        //dd($request->all());


        $user = User::create([
            'name' => $request->get('name'),
            'username' => $request->get('username'),
            'email' => $request->get('email'),
            'whatsapp' => $request->get('whatsapp'),
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
