<?php

namespace CustomPackages\CustomApp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackLastActive
{
    public function handle(Request $request, Closure $next)
    {
        if (! config('custom-app.track_user_last_active_time') || ! $request->user('custom-app')) {
            return $next($request);
        }

        if (! $request->user('custom-app')->last_active_at || $request->user('custom-app')->last_active_at->isPast()) {
            $request->user('custom-app')->last_active_at = now();

            $request->user('custom-app')->saveQuietly();
        }

        return $next($request);
    }
}
