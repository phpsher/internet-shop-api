<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\HttpStatus;

class AdminMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role->role !== 'admin') {
            return $this->error(
                message: 'You are not admin',
                statusCode: HttpStatus::UNAUTHORIZED->value
            );
        }

        return $next($request);
    }
}
