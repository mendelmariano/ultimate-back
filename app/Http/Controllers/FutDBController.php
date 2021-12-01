<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;



class FutDBController extends Controller
{
    //

    public function index(Request $request){

        $token = '7cfc3e83-e4a8-428d-a20d-857367546f26';
        $type = 'X-AUTH-TOKEN';

        $response = Http::withHeaders([$type => $token])->get('https://futdb.app/api/clubs');
        $clubsArray = $response->json();

        return response()->json($clubsArray);



    }
}
