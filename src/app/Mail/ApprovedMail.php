<?php

namespace App\Mail;

use App\Models\Content;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content as MailContent;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 投稿した内容または項目が承認された際に投稿ユーザーへ通知するメール
// Content と Item の両方に対応する
class ApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Content|Item $model,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【公開のお知らせ】投稿した内容が承認されました',
        );
    }

    public function content(): MailContent
    {
        return new MailContent(
            view: 'emails.approved',
            with: [
                'url' => route('animal_welfare.edit'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
