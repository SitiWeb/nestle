<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roleName)
    {   
        if ($roleName === 'editor' && auth()->user()->hasRole('admin')){
            return $next($request);
        }
        if ($roleName === 'reader' && auth()->user()->hasRole('admin')){
            return $next($request);
        }
        if ($roleName === 'reader' && auth()->user()->hasRole('editor')){
            return $next($request);
        }
        if (!auth()->check() || !auth()->user()->hasRole($roleName)) {

            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
