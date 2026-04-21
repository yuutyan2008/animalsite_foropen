<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .content-box { background: #f8fafc; border-left: 4px solid #94a3b8; padding: 12px 16px; margin: 16px 0; font-size: 14px; }
        .label { font-weight: bold; color: #475569; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>投稿内容への出典追記のお願い</h1>

        <p>いつもご利用いただきありがとうございます。<br>
        あなたの投稿内容について、管理者より出典（根拠・参考資料）の追記をお願いしたい箇所があります。</p>

        @php
            $content = $referenceNeeded->content;
            $opinionSentences = $content?->sentences->filter(fn($s) => $s->type === \App\Models\ContentSentence::TYPE_OPINION) ?? collect();
        @endphp

        <p class="label">■ 対象の投稿</p>
        <div class="content-box">
            <p><strong>テーマ：</strong>{{ $content?->item?->theme?->name ?? '—' }}</p>
            <p><strong>項目：</strong>{{ $content?->item?->name ?? '—' }}</p>
            @if($opinionSentences->isNotEmpty())
            <p><strong>考察・意見の内容：</strong></p>
            <ul>
                @foreach($opinionSentences as $sentence)
                <li>{{ $sentence->value }}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <p>お手数ですが、投稿内容を編集し、考察・意見の根拠となる出典（書籍・論文・ウェブサイト等）を追記いただけますと幸いです。</p>

        <p>なお、出典の追記がない場合、掲載を継続できない場合があります。</p>

        <p>ご不明な点がございましたら、<a href="{{ route('posting_guide') }}">投稿ガイド</a>をご参照ください。</p>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
