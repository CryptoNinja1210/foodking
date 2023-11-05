<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class localization
{
    public function handle(Request $request, Closure $next)
    {
        $local = ($request->hasHeader('x-localization')) ? $request->header('x-localization') : 'en';
        App::setLocale($local);
        return $next($request);
    }
}
