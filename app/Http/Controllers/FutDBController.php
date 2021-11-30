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

        $response = Http::withToken('X-AUTH-TOKEN', '7cfc3e83-e4a8-428d-a20d-857367546f26')->get('https://futdb.app/api/clubs')->json();

        dd($response);
        return

        $token = '7cfc3e83-e4a8-428d-a20d-857367546f26';
        $type = 'X-AUTH-TOKEN';

        $response = Http::withOptions([
            $type => $token
        ])->get('https://futdb.app/api/clubs');

        $clubsArray = $response->json();

        return response()->json($clubsArray);

        /* $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://futdb.app/api',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);



        $response = $client->request('GET', '/clubs');

        return response()->json($response); */

        //return Http::dd()->get('https://futdb.app/api/clubs');


       /*  $response = Http::get('https://futdb.app/api/clubs');
        $clubsArray = $response->json();
        //dd($response);
        return response()->json($clubsArray); */


       /* // $client = new \GuzzleHttp\Client();
        $token = '7cfc3e83-e4a8-428d-a20d-857367546f26';
        $type = 'X-AUTH-TOKEN';
        $request =  Http::get('http://jsonplaceholder.typicode.com/todos/1');
        //dd($request);
        $clubsArray = $request->json();
        //dd($request);
        return response()->json($clubsArray); */

        //dd($response);

    }
}
