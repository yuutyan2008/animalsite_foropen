<?php

namespace App\Mail;

use App\Models\ReferenceNeeded;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 要出典として登録された投稿ユーザーへ出典の追記を依頼するメール
class ReferenceNeededMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ReferenceNeeded $referenceNeeded
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【出典のご確認】投稿内容への出典追記のお願い',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reference_needed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
