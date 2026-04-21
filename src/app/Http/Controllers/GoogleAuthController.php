<?php

namespace App\Http\Controllers;

use App\Models\BannedGoogleId;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    // Googleの認証画面へリダイレクト
    public function redirect()
    {
        // stateless()：セッションを使ったstateの照合を行わない設定
        // 複数のGoogleアカウントがブラウザにログインしている場合、
        // stateの不一致によりInvalidStateExceptionが発生するため、これを防ぐ
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Google認証後のコールバック処理
    public function callback()
    {
        // redirect()と合わせてstateless()を使う（セットで使う必要がある）
        $googleUser = Socialite::driver('google')->stateless()->user();

        // BANリストに登録されているgoogle_idはログイン・新規登録を拒否
        if (BannedGoogleId::isBanned($googleUser->getId())) {
            return redirect()->route('login')
                ->with('error', 'アカウントが停止されています。');
        }

        // google_idが一致するユーザを探す、なければメールで検索して紐付け、それもなければ新規作成
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // 既存ユーザにgoogle_idを紐付け（まだ紐付いていない場合）
            // name・role は既存の値を維持し、上書きしない
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }
        } else {
            // 新規ユーザを作成（パスワードなし、ロールは一般ユーザ）
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'password'  => null,
                'role'      => User::ROLE_USER,
            ]);
        }

        // BANされているユーザーはログイン不可
        if ($user->is_banned) {
            return redirect()->route('login')
                ->with('error', 'アカウントが停止されています。');
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('animal_welfare.edit'));
    }
}
