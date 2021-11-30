<?php

namespace App\Helpers;


use App\Models\Result;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use Illuminate\Http\Request;
use App\Models\Club;

class ResultadoHelper {
    public static function teste(){
        return "Helper meu";
    }

    public function computaResultado(Result $result)
    {
        
        //dd("req: ", $request->all());
       $gols_casa = $result->gols_casa;
       $gols_fora = $result->gols_fora;
       $club_casa = $result->casa_id;
       $club_fora = $result->fora_id;

       if($gols_casa == $gols_fora){
        $clubs = [$club_casa, $club_fora];   
        
        $this->registraEmpate($clubs, $gols_casa);
       }else{
           if($gols_casa > $gols_fora){
                $this->registraVitoria($club_casa, $gols_casa, $gols_fora);
                $this->registraDerrota($club_fora, $gols_fora, $gols_casa);
           }

           if($gols_casa < $gols_fora){
                $this->registraVitoria($club_fora, $gols_casa, $gols_fora);
                $this->registraDerrota($club_casa, $gols_fora, $gols_casa);
           }
       }



       return response()->json([
        'message' => 'Resultado computado com sucesso!'
], 200);

    
    }


    public function registraEmpate($times, $gols){
        foreach($times as $time){
         $time_up = Club::find($time);
         $time_up->empates++;
         $time_up->pontos++;
         $time_up->gols_feitos += $gols;
         $time_up->gols_sofridos += $gols;
         $time_up->save();

        }
        return response()->json([
            'message' => 'Empate registrado com sucesso!'
    ], 200);
    }

    public function registraVitoria($time, $gols_feitos, $gols_sofridos){
        $time_up = Club::find($time);        
        $time_up->vitorias++;         
         $time_up->gols_feitos += $gols_feitos;
         $time_up->jogos++;
         $time_up->pontos += 3;         
         $time_up->gols_sofridos += $gols_sofridos;
         $time_up->saldo_gols = $time_up->gols_feitos - $time_up->gols_sofridos;
         $time_up->save();

         return response()->json([
            'message' => 'Vitória registrada com sucesso!'
    ], 200);
        
    }

    public function registraDerrota($time, $gols_feitos, $gols_sofridos){
        $time_up = Club::find($time);
        $time_up->jogos++;
        $time_up->derrotas++;         
        $time_up->gols_feitos += $gols_feitos;
        $time_up->gols_sofridos += $gols_sofridos;
        $time_up->saldo_gols = $time_up->gols_feitos - $time_up->gols_sofridos;
        $time_up->save();

         return response()->json([
            'message' => 'Derrota registrado com sucesso!'
    ], 200);
    }
}


