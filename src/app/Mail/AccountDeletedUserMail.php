<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 退会完了をユーザー本人へ通知するメール
class AccountDeletedUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '退会処理が完了しました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account_deleted_user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
