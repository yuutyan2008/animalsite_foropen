<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 違反報告が届いた際に管理者へ通知するメール
class ReportedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【違反報告】内容についての報告が届きました',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.content_reported',
            with: [
                'url' => route('admin.social_issues.reports.index'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
