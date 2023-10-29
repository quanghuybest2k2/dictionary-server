<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAdminMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            if (auth()->user()->tokenCan('server:admin')) {
                return $next($request);
            } else {
                return $this->responseError('403 Forbidden', 'Bạn không có quyền truy cập!', Response::HTTP_FORBIDDEN);
            }
        } else {
            return $this->responseError('401 unauthorized', 'Vui lòng đăng nhập!', Response::HTTP_NOT_FOUND);
        }
    }
}
