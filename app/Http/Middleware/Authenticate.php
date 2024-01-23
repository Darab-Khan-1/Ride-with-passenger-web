<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : url('/');
    }
    protected function unauthenticated($request, array $guards)
    {
        if(request()->hasHeader('Authorization') || $request->is('api/*')){
            abort(response()->json(
                [
                    'status_code' => 401,
                    'message' => 'Unauthenticated',
                    'error'=> "user access token is missing or expired",
                    'data' => []
                ], 401));
        }
    
        Parent::unauthenticated($request, $guards);
        
    }

}
