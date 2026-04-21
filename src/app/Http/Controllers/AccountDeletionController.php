<?php

namespace App\Http\Controllers;

use App\Mail\AccountDeletedMail;
use App\Mail\AccountDeletedUserMail;
use App\Models\BannedGoogleId;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AccountDeletionController extends Controller
{
    // 退会確認ページを表示
    public function confirm()
    {
        return view('account.delete_confirm');
    }

    // アカウントを削除して退会処理
    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // 削除前にユーザー情報を保存（削除後は取得できなくなるため）
        $userName    = $user->name;
        $userEmail   = $user->email;
        $reason      = $request->input('reason');
        $reasonOther = $request->input('reason_other');

        // セッションを無効化してログアウト
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // BANされているユーザーが退会しても再入会できないようgoogle_idをBANリストに残す
        // 通常の退会はgoogle_idを削除して再入会を許可する
        if ($user->is_banned && $user->google_id) {
            BannedGoogleId::firstOrCreate(['google_id' => $user->google_id]);
        }

        // 個人情報を削除してからアカウントを削除
        $user->update([
            'name'      => '退会済みユーザー',
            'email'     => 'deleted_' . $user->id . '@deleted',
            'google_id' => null,
        ]);
        $user->delete();

        // 管理者に退会通知メールを送信
        Mail::to(config('mail.from.address'))
            ->send(new AccountDeletedMail($userName, $userEmail, $reason, $reasonOther));

        // ユーザー本人に退会完了メールを送信
        Mail::to($userEmail)
            ->send(new AccountDeletedUserMail($userName));

        return redirect()->route('animal_welfare.edit')
            ->with('success', '退会処理が完了しました。ご利用ありがとうございました。');
    }
}
