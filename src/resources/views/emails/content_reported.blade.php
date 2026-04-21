<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .content-box { background: #fff5f5; border-left: 4px solid #e74c3c; padding: 12px 16px; margin: 16px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>違反報告通知</h1>

        {{-- content_id が存在する場合：内容への報告 --}}
        @if($report->content)
        <p>内容についての違反報告が届きました。</p>

        <p><strong>■ 報告された内容</strong></p>
        <div class="content-box">
            @foreach($report->content->sentences as $sentence)
            <p>{{ $sentence->value }}</p>
            @if($sentence->url)
            <p>出典：<a href="{{ $sentence->url }}">{{ $sentence->url_title ?: $sentence->url }}</a></p>
            @endif
            @endforeach
        </div>

        <p><strong>■ 項目</strong></p>
        <p>{{ $report->content->item->name }}</p>

        {{-- item_id が存在する場合：項目への報告 --}}
        @elseif($report->item)
        <p>項目についての違反報告が届きました。</p>

        <p><strong>■ 報告された項目</strong></p>
        <div class="content-box">
            <p>{{ $report->item->name }}</p>
        </div>

        <p><strong>■ テーマ</strong></p>
        <p>{{ $report->item->theme?->name ?? '―' }}</p>

        @endif

        <p><strong>■ 報告理由</strong></p>
        <p>{{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}</p>

        <p><strong>■ 報告者</strong></p>
        <p>{{ $report->user?->name ?? '不明' }}</p>

        <p>--------------------------------------------------</p>
        <p>
            以下の管理画面より内容を確認し、必要に応じて削除してください。<br>
            <a href="{{ $url }}">{{ $url }}</a>
        </p>
        <p>--------------------------------------------------</p>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
