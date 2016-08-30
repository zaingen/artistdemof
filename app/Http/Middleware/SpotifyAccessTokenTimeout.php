<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;

class SpotifyAccessTokenTimeout
{
    protected $session;
    protected $timeout = 3600;

    public function __construct(Store $session){
        $this->session = $session;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        var_dump($request);
        exit;
        if(! session('access_token_time'))
            $this->session->put('access_token_time', time());
        elseif(time() - $this->session->get('access_token_time') > $this->timeout){
            $this->session->forget('access_token_time');
            $cookie = cookie('intend', $isLoggedIn ? url()->current() : 'dashboard');
            $email = $request->user()->email;
            auth()->logout();
            return message('You had not activity in '.$this->timeout/60 .' minutes ago.', 'warning', 'login')->withInput(compact('email'))->withCookie($cookie);
        }
        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);
    }
}
