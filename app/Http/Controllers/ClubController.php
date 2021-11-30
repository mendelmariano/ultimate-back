<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Http\Requests\StoreClubRequest;
use App\Http\Requests\UpdateClubRequest;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Club $club)
    {
        //
        return response()->json($club->with("user")->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClubRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClubRequest $request)
    {
        //
        $newClub = Club::create($request->all());
        return response()->json($newClub, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Club  $Club
     * @return \Illuminate\Http\Response
     */
    public function show(Club $club)
    {
        //
        return response()->json($club->load('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Club  $Club
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $club)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClubRequest  $request
     * @param  \App\Models\Club  $Club
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClubRequest $request, Club $club)
    {
        //

        $club->update($request->all());
        return response()->json($club);
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Club  $Club
     * @return \Illuminate\Http\Response
     */
    public function destroy(Club $club)
    {
        //
        try {
            $club->delete();
            
            return response()->json([
                    'message' => 'Clube excluído com sucesso!'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir o Clube."
            ], 500);
        }
    }

   


}
