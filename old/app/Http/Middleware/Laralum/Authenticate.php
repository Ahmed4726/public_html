<?php

namespace App\Http\Middleware\Laralum;

use Closure;
use Auth;
use Laralum;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Laralum::checkInstalled()) {
            if(Auth::check()) {
                $user = Laralum::loggedInuser();
                if($user->state_id!=1) {
                    Auth::logout();
                    return redirect('login')
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors('These credentials do not enabled our records.');
                }
//                Laralum::mustBeAdmin($user);
            } else {
                return redirect('/')->with('error', 'You are not logged in');
            }
        }
        return $next($request);
    }
}
