<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FutDB
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = '7cfc3e83-e4a8-428d-a20d-857367546f26';
        $type = 'X-AUTH-TOKEN';

        $request->header($type, $token);



        return $next($request);
    }
}
