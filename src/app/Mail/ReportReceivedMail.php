<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 違反報告を受け付けた際に報告者へ送る受付確認メール
class ReportReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Report $report,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【違反報告】報告を受け付けました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report_received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
