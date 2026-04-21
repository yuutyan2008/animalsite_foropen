<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 掲載継続の場合に違反報告ユーザーへ送るメール
class ReportKeptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Report $report,
        public ?string $adminComment // 管理者の自由記述（nullable）
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【違反報告の結果】ご報告の内容について',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report_kept',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
