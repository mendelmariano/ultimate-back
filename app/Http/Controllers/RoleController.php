<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    //
    public function attachRole(Request $request)
    {
        //

        $user_id = $request->user_id;
        $role_selected = $request->role;
        $user = User::find($user_id);
        $user->assignRole($role_selected);


        return response()->json($user->getRoleNames());

    }

    public function detachRole(Request $request)
    {
        //

        $user_id = $request->user_id;
        $role_selected = $request->role;
        $user = User::find($user_id);
        $user->removeRole($role_selected);

        return response()->json($user->getRoleNames());

    }

    public function showRolesForUser($id)
    {
        //


        $user = User::find($id);
        return response()->json($user->getRoleNames());

    }

    public function showRolesUserLogged()
    {
        //
        $user = Auth::user();
        //dd($user);
        //return response()->json($user->getRoleNames());

    }
}
