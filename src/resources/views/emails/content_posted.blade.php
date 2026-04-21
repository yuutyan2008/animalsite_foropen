<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #27ae60; padding-bottom: 10px; }
        .content-box { background: #f8f9fa; border-left: 4px solid #27ae60; padding: 12px 16px; margin: 16px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>新しい投稿通知</h1>

        <p>項目に新しい内容が投稿されました。</p>

        <p><strong>■ 項目</strong></p>
        <p>{{ $content->item->name }}</p>

        <p><strong>■ 投稿内容</strong></p>
        <div class="content-box">
            @foreach($content->sentences as $sentence)
            <p>{{ $sentence->value }}</p>
            @if($sentence->url)
            <p>出典：<a href="{{ $sentence->url }}">{{ $sentence->url_title ?: $sentence->url }}</a></p>
            @endif
            @endforeach
        </div>

        <p><strong>■ 投稿者</strong></p>
        <p>{{ $content->user?->name ?? '不明' }}</p>

        <p>--------------------------------------------------</p>
        <p>
            以下のページより内容を確認してください。<br>
            <a href="{{ $url }}">{{ $url }}</a>
        </p>
        <p>--------------------------------------------------</p>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
