<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        //listar todos os usuários

        return response()->json($user->with("clubs")->get());
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        return response()->json($user->load('clubs'));
    }

    public function destroy(User $user)
    {
        //
        try {
            $user->delete();
            
            return response()->json([
                    'message' => 'Usuario excluído com sucesso!'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir o usuario."
            ], 500);
        }
    }

}
