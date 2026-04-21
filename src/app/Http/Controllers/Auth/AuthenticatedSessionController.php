<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // 1. ログインしたユーザー情報を取得
        $user = $request->user();

        // 一般ユーザー・管理者は、本来行こうとしていたページ（または標準のダッシュボード）へ
        return redirect()->intended(route('animal_welfare.edit', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     * ログアウト時の処理
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // 一覧にメッセージ付きで飛ばす
        return redirect()->route('animal_welfare.edit')
            ->with('success', 'ログアウトしました。');
    }
}
