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
        <h1>投稿した内容が承認されました</h1>

        <p>{{ $model->user?->name }} 様</p>
        <p>ご投稿いただいた内容が承認され、サイトに公開されました。</p>

        {{-- Content（内容）の場合：テーマ/項目と本文を表示 --}}
        @if($model instanceof \App\Models\Content)

        <p><strong>■ テーマ / 項目</strong></p>
        <div class="content-box">
            <p>{{ $model->item?->theme?->name }} / {{ $model->item?->name }}</p>
        </div>

        <p><strong>■ 投稿内容</strong></p>
        <div class="content-box">
            @foreach($model->sentences as $sentence)
            <p>{{ $sentence->value }}</p>
            @endforeach
        </div>

        {{-- Item（項目）の場合：テーマと項目名を表示 --}}
        @else

        <p><strong>■ テーマ</strong></p>
        <div class="content-box">
            <p>{{ $model->theme?->name }}</p>
        </div>

        <p><strong>■ 項目名</strong></p>
        <div class="content-box">
            <p>{{ $model->name }}</p>
        </div>

        @endif

        <p>
            以下のページでご確認いただけます。<br>
            <a href="{{ $url }}">{{ $url }}</a>
        </p>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
