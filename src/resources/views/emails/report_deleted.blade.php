<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .content-box { background: #f8fafc; border-left: 4px solid #94a3b8; padding: 12px 16px; margin: 16px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        @if($recipientType === 'poster')
        {{-- 投稿ユーザー向け --}}
        <h1>投稿の削除について</h1>

        <p>いつもご利用いただきありがとうございます。<br>
        誠に恐れ入りますが、あなたの投稿について違反報告があり、管理者が確認した結果、<strong>削除</strong>させていただきました。</p>

        <p><strong>■ 削除の理由</strong></p>
        <p>{{ \App\Models\Report::REASONS[$deleteReason] ?? $deleteReason }}</p>

        @if($adminComment)
        <p><strong>■ 管理者からのコメント</strong></p>
        <div class="content-box">
            <p>{{ $adminComment }}</p>
        </div>
        @endif

        <p>今後とも<a href="{{ route('posting_guide') }}">投稿ガイド</a>をご確認のうえ、ご投稿いただけますと幸いです。</p>

        @else
        {{-- 報告ユーザー向け --}}
        <h1>違反報告の結果について</h1>

        <p>この度はご報告いただきありがとうございます。<br>
        管理者にて内容を確認いたしました結果をお知らせします。</p>

        <p><strong>■ ご報告いただいた理由</strong></p>
        <p>{{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}</p>
        @if($report->other_detail)
        <p>詳細：{{ $report->other_detail }}</p>
        @endif

        <p><strong>■ 対応結果</strong></p>
        <p>確認の結果、当該投稿を<strong>削除</strong>いたしました。ご報告いただきありがとうございました。</p>

        @if($adminComment)
        <p><strong>■ 管理者からのコメント</strong></p>
        <div class="content-box">
            <p>{{ $adminComment }}</p>
        </div>
        @endif

        @endif

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
