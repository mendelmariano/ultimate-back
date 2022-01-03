<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\Environment\Console;

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


     /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClubRequest  $request
     * @param  \App\Models\Club  $Club
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //


        $user = User::find($request->id);

        if(isset($request->menuIds)){
            $menus = implode(",", $request->menuIds);
            $user->menuIds = $menus;
        }

        if(isset($request->active)){
            $user->active = $request->active;
            //65432111111111111764352
            //return error_log("errou");

        }

        $user->name = $request->name;

        $user->username = $request->username;
        $user->email = $request->email;
        $user->whatsapp = $request->whatsapp;

        if(isset($request->password)){
        $user->password = Hash::make($request->get('password'));
        }


        $user->save();

        //$user->update($request->all());
        //Log::info($user);
        return response()->json($user);

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
            Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir o usuario."
            ], 500);
        }
    }


    public function clubsForUser(User $user)
    {
        //
        try {
            Log::info($user->id);
            $user_complete = $user->load('clubs');
            $clubs = $user_complete->clubs;

            return response()->json($clubs, 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir o Clube."
            ], 500);
        }
    }

}
