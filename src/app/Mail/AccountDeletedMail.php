<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 退会ユーザーの情報を管理者へ通知するメール
class AccountDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $userEmail;
    public ?string $reason;
    public ?string $reasonOther;

    public function __construct(string $userName, string $userEmail, ?string $reason, ?string $reasonOther)
    {
        $this->userName    = $userName;
        $this->userEmail   = $userEmail;
        $this->reason      = $reason;
        $this->reasonOther = $reasonOther;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【退会通知】ユーザーが退会しました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.account_deleted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
