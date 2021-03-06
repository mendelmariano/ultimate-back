<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Helpers\ResultadoHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Result $result)
    {
        //
        return response()->json($result->with(["usuario_casa", "usuario_fora"])->get());
    }

    public function forUserConfirm(Result $result)
    {
        $user = Auth::user();
        $clubsUser = Club::where('user_id', $user->id)->with("user")->get()->toArray();
        $idClubs = [];

        //dd($clubsUser);

        foreach($clubsUser as $club){
            //dd($club["id"]);
            array_push($idClubs, $club["id"]);
        }
        //dd($idClubs);

        $results = Result::where('status', 0)
        ->where(function($query) use ($idClubs){
            $query->WhereIn('fora_id', $idClubs)
                ->orWhereIn('casa_id', $idClubs);
        })

        ->with(["usuario_casa", "usuario_fora"])
        ->get()->toArray();


        return response()->json($results);
    }



    public function forUserConfirmed(Result $result)
    {
        $user = Auth::user();
        $clubsUser = Club::where('user_id', $user->id)->with("user")->get()->toArray();
        $idClubs = [];

        //dd($clubsUser);

        foreach($clubsUser as $club){
            //dd($club["id"]);
            array_push($idClubs, $club["id"]);
        }
        //dd($idClubs);

        $results = Result::where('status', '>', 0)
        ->where(function($query) use ($idClubs){
            $query->WhereIn('fora_id', $idClubs)
                ->orWhereIn('casa_id', $idClubs);
        })

        ->with(["usuario_casa", "usuario_fora"])
        ->get()->toArray();


        return response()->json($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResultRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResultRequest $request)
    {
        //
        Log::info($request->all());
        $newResult = Result::create($request->all());
        return response()->json($newResult, 201);
    }



    public function storeClassification(StoreResultRequest $request)
    {
        //
        $newResult = Result::create($request->all());

        return response()->json($newResult, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function show(Result $result)
    {
        //
        return response()->json($result->load(["usuario_casa", "usuario_fora"]));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function edit(Result $result)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResultRequest  $request
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResultRequest $request, Result $result)
    {
        //
        $result->update($request->all());
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Result  $result
     * @return \Illuminate\Http\Response
     */
    public function destroy(Result $result)
    {
        //
        try {
            $result->delete();

            return response()->json([
                    'message' => 'Resultado exclu??do com sucesso!'
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error($e->getMessage());
            return response()->json([
                'error' =>  "Erro ao excluir resultado."
            ], 500);
        }
    }


    public function confirmaResultado($id){
        //dd(ResultadoHelper::teste());

        try{
                $result = Result::find($id);
                if($result->status == 0){
                $resultadoHelper = new ResultadoHelper();
                $resultadoHelper->computaResultado($result);
                $result->status = 1;
                $result->save();
                    return response()->json([
                        'message' => 'Resultado confirmado com sucesso!'
                    ], 200);
                }else{
                    return response()->json([
                        'message' => 'Seu resultado so pode ser confirmado caso esteja na lista de espera!'
                    ], 200);
                }

         } catch (\Illuminate\Database\QueryException $e) {
                Log::error($e->getMessage());
                return response()->json([
                    'error' =>  "Erro ao confirmar resultado."
                ], 500);
    }

    }

    public function computaResultados(Request $request){

        //dd(ResultadoHelper::teste());

        try{
                //dd($request->ids);
                $results = Result::whereIn('id', $request->ids)->get();
                //dd($results);
                 if($request->tipo == 1 ){
                     $status = 1;
                     $message = "Resultados confirmados com sucesso!";
                 }

                 if($request->tipo == 2 ){
                    $status = 2;
                    $message = "Resultados rejeitados com sucesso!";
                }

                 foreach($results as $result){
                        //echo "passou";
                        //dd($result->status);
                        if($result->status == 0){


                        $resultadoHelper = new ResultadoHelper();
                        $resultadoHelper->computaResultado($result);
                        $result->status = $status;
                        $result->save();


                        }
                    }
                    return response()->json([
                        'message' => $message
                    ], 200);
            }


          catch (\Illuminate\Database\QueryException $e) {
                Log::error($e->getMessage());
                return response()->json([
                    'error' =>  "Erro ao confirmar resultado."
                ], 500);
    }

    }


    public function rejeitaResultado($id){
        //dd(ResultadoHelper::teste());

        try{
                $result = Result::find($id);
                $result->status = 2;
                $result->save();
                return response()->json([
                    'message' => 'Resultado rejeitado com sucesso!'
                ], 200);
         } catch (\Illuminate\Database\QueryException $e) {
                Log::error($e->getMessage());
                return response()->json([
                    'error' =>  "Erro ao rejeitado resultado."
                ], 500);
    }

    }



}
