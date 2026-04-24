<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogQueries
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::enableQueryLog();

        $response = $next($request);

        if (app()->environment('local')) {
            \Log::info('SQL Queries for URL: ' . $request->fullUrl(), [
                'queries' => DB::getQueryLog(),
                'bindings' => DB::getQueryLog()
            ]);
        }

        return $response;
    }
}
