<?php

use App\Http\Middleware\CheckBanned;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // RenderのロードバランサーはユーザーとのHTTPS通信を行い復号し、コンテナにはHTTPで転送する
        // その際「元はHTTPSだった」という情報をX-Forwarded-Protoヘッダーに付けて送る。
        // このヘッダーを信頼することで、LaravelがHTTPSと正しく認識してアセットURLをhttps://で生成できる。
        $middleware->trustProxies(at: '*');
        $middleware->appendToGroup('web', CheckBanned::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
