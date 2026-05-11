<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HandleInertiaRequests
{
    protected string $rootView = 'layouts.app';

    public function handle(Request $request, Closure $next)
    {
        Inertia::setRootView($this->rootView);

        Inertia::share([
            'auth' => [
                'user' => $request->user(),
            ],
        ]);

        return $next($request);
    }
}