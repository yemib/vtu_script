<?php

namespace App\Http\Middleware;

use App\Models\GeneralSetting;
use Closure;
use Illuminate\Http\Request;

class checkKycMiddleWare
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

        $general    = GeneralSetting::first();
        $user       = auth()->user();

        if($general->modules->kyc && !$user->kycv){
            $notify[]=['error','You need to complete KYC verification for this action'];
            return redirect()->route('user.kyc.verify')->withNotify($notify);
        }
        return $next($request);

    }
}
