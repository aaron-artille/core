<?php namespace Flarum\Forum\Middleware;

use Flarum\Support\Actor;
use Flarum\Core\Models\AccessToken;
use Auth;
use Closure;

class LoginWithCookie
{
    protected $actor;

    public function __construct(Actor $actor)
    {
        $this->actor = $actor;
    }

    public function handle($request, Closure $next)
    {
        if (($token = $request->cookie('flarum_remember')) &&
            ($accessToken = AccessToken::where('id', $token)->first())) {
            $this->actor->setUser($user = $accessToken->user);

            $user->updateLastSeen()->save();
        }

        return $next($request);
    }
}
