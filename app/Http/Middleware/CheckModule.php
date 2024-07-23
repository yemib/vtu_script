<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;

class CheckModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $name)
    {
        $general = GeneralSetting::first();

        if($general->modules->$name){
            return $next($request);
        }else{
            $notify[] = ['error', 'Sorry '.ucfirst($name).' Is Not Available Now'];
            return redirect()->route('user.home')->withNotify($notify);
        }
    }
}
