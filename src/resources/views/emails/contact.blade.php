<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        h1 { font-size: 20px; color: #2c3e50; border-bottom: 2px solid #0ea5e9; padding-bottom: 10px; }
        .content-box { background: #f8f9fa; border-left: 4px solid #0ea5e9; padding: 12px 16px; margin: 16px 0; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <h1>お問い合わせが届きました</h1>

        <p><strong>■ 送信者情報</strong></p>
        <div class="content-box">
            <p>お名前：{{ $data['name'] }}</p>
            <p>メール：{{ $data['email'] }}</p>
        </div>

        <p><strong>■ 件名</strong></p>
        <div class="content-box">
            <p>{{ $data['subject'] }}</p>
        </div>

        <p><strong>■ お問い合わせ内容</strong></p>
        <div class="content-box">
            <p style="white-space: pre-wrap;">{{ $data['body'] }}</p>
        </div>

        <p class="footer">※このメールはシステムより自動送信されています。</p>
    </div>
</body>
</html>
