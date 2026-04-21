<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        // デバッグログ
        \Log::info('CheckBanned: check=' . (Auth::check() ? 'true' : 'false') . ', banned=' . (Auth::check() ? Auth::user()->is_banned : 'N/A'));

        // ログイン中かつBANされている場合は強制ログアウト
        if (Auth::check() && Auth::user()->is_banned) {
            Auth::logout();
            // セッションID（クッキーの abc123）を無効にして再利用できなくする
            $request->session()->invalidate();
            // 新しいCSRFトークンを発行してセキュリティを保つ
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'このアカウントは利用を停止されています。');
        }

        return $next($request);
    }
}
