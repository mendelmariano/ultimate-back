<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


use App\Models\User;

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

        $token = $user->createToken($request->email.strtotime("now"))->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "token_type" => 'bearer'
        ]);

    }

    public function register(Request $request){

        $dados = $request->all();
        
        $dados['password'] = Hash::make($request->password);        
        $newUser = User::create($dados);
        return response()->json($newUser, 201);
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
            \Log::error($e->getMessage());
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
}
