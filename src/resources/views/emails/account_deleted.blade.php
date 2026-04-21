<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #e74c3c; padding-bottom: 10px; }
        .content-box { background: #f8f9fa; border-left: 4px solid #e74c3c; padding: 12px 16px; margin: 16px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>ユーザーが退会しました</h1>

        <p><strong>■ ユーザー情報</strong></p>
        <div class="content-box">
            <p>名前：{{ $userName }}</p>
            <p>メール：{{ $userEmail }}</p>
        </div>

        <p><strong>■ 退会理由</strong></p>
        <div class="content-box">
            @php
                $reasonLabels = [
                    'not_useful'       => '情報が少なく使えなかった',
                    'unsatisfied'      => '内容に満足できなかった',
                    'hard_to_use'      => '使い方がわかりにくかった',
                    'no_longer_needed' => 'もう必要がなくなった',
                    'other'            => 'その他',
                ];
            @endphp

            @if($reason)
                <p>{{ $reasonLabels[$reason] ?? $reason }}</p>
                @if($reason === 'other' && $reasonOther)
                    <p>自由記述：{{ $reasonOther }}</p>
                @endif
            @else
                <p>回答なし</p>
            @endif
        </div>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
