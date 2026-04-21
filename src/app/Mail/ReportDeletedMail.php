<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// 削除の場合に投稿ユーザーまたは報告ユーザーへ送るメール
// $recipientType で送信先を区別する（'poster' = 投稿ユーザー, 'reporter' = 報告ユーザー）
class ReportDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Report $report,
        public string $deleteReason,      // 判断基準から選んだ理由
        public ?string $adminComment,     // 管理者の自由記述（nullable）
        public string $recipientType      // 'poster' or 'reporter'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'poster'
            ? '【投稿の削除について】違反報告により投稿が削除されました'
            : '【違反報告の結果】ご報告の内容について対応しました';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.report_deleted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
