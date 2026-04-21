<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class CustomResetPassword extends ResetPasswordNotification

{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     * 通知メールの内容をカスタマイズするために、toMailメソッドをオーバーライド
     */
    public function toMail($notifiable): MailMessage
    {
        // パスワードリセット用のURLを生成します
        // $this->token は親クラスから引き継いでいます
        $url = url(config('app.url') . route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('パスワード再設定手続きのご案内')
            ->greeting('いつもご利用いただき、誠にありがとうございます。')
            ->line('本メールは、アカウントのパスワード再設定リクエストをいただいたお客様へ送信しております。')
            ->line('下記のボタンをクリックし、パスワードの再設定手続きを進めてください。')
            ->action('パスワードを再設定する', $url)
            ->line('なお、本リンクの有効期限は発行から60分間となっております。')
            ->line('本メールにお心当たりがない場合は、メールを破棄していただきますようお願い申し上げます。')
            ->salutation('引き続き、よろしくお願い申し上げます。');
    }
}
