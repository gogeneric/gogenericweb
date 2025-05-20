<?php

namespace App\Http\Middleware;

use App\Traits\ActivationClass;
use Closure;

class InstallationMiddleware
{
    use ActivationClass;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->is_local()) {
            return $next($request); // Skip purchase code check if local
        }
        dd('here');
        if (session()->has('purchase_key') == false && env('PURCHASE_CODE') == null) {
            session()->flash('error', base64_decode('SW52YWxpZCBwdXJjaGFzZSBjb2RlIGZvciB0aGlzIHNvZnR3YXJlLg=='));
            return redirect('step2');
        }elseif(env('PURCHASE_CODE') != null){
            return $next($request);
        }

        return $next($request);
    }
}
