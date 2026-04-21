<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ログインしていない、または role が ADMIN (1) でない場合
        if (!auth()->check() || auth()->user()->role !== User::ROLE_ADMIN) {
            abort(403, '管理者権限が必要です。');
        }
        return $next($request);
    }
}
