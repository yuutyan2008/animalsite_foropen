<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
        }

        h1 {
            font-size: 20px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>退会処理が完了しました</h1>

        <p>{{ $userName }} 様</p>

        <p>Solvedienceをご利用いただきありがとうございました。</p>
        <p>退会処理が正常に完了しました。アカウント情報はすべて削除されました。</p>
        <p>またのご利用をお待ちしております。</p>

        <p class="footer">※このメールはシステムより自動送信されています。返信はできません。</p>
    </div>
</body>

</html>
