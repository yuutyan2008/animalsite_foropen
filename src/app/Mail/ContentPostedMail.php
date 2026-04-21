<?php

namespace App\Mail;

use App\Models\Content as ContentModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 一般ユーザーが内容を投稿した際に管理者へ承認を促す通知メール
class ContentPostedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public function __construct(ContentModel $content)
    {
        $this->content = $content;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【投稿通知】項目に新しい内容が追加されました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.content_posted',
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
