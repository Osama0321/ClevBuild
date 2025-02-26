<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Bouncer;
class ability
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role, $ag = null)
    {   
        if (!Bouncer::can($role) && $ag == null) {
            return abort('403', 'You dont have access to this page');
        }
        else if($ag != null && !Bouncer::can($ag)){
            return abort('403', 'You dont have access to this page');
        }
        return $next($request);
    }
}
