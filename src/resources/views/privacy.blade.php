@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-bold text-slate-800 mb-8">プライバシーポリシー</h1>

    <div class="prose prose-slate max-w-none space-y-8 text-slate-700 text-sm leading-relaxed">

        <p>本サービス「Solvedience」（以下「当サービス」）は、ユーザーの個人情報の取り扱いについて、以下のとおりプライバシーポリシーを定めます。</p>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">1. 取得する個人情報</h2>
            <p>当サービスでは、Googleアカウントによるログイン時に、Google LLC よりご提供いただいた以下の情報を取得します。</p>
            <ul class="list-disc list-inside space-y-1 mt-2">
                <li>メールアドレス</li>
                <li>ユーザー名</li>
                <li>アクセスログ（IPアドレス、ブラウザ情報等）</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">2. 個人情報の利用目的</h2>
            <p>取得した個人情報は、以下の目的のために利用します。</p>
            <ul class="list-disc list-inside space-y-1 mt-2">
                <li>会員サービスの提供・運営</li>
                <li>ログイン認証・本人確認</li>
                <li>お問い合わせへの対応</li>
                <li>サービスの改善・新機能の開発</li>
                <li>不正利用の防止</li>
            </ul>
            <p class="mt-3">なお、利用規約に違反したユーザーのアカウントを停止した場合、再入会による不正利用を防止する目的で、当該ユーザーのGoogleアカウント識別子（google_id）を保持することがあります。この情報は不正利用防止以外の目的には使用しません。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">3. 第三者への提供</h2>
            <p>当サービスは、以下の場合を除き、ユーザーの個人情報を第三者に提供しません。</p>
            <ul class="list-disc list-inside space-y-1 mt-2">
                <li>ユーザー本人の同意がある場合</li>
                <li>法令に基づく場合</li>
                <li>人の生命・身体・財産の保護のために必要な場合</li>
            </ul>
            <p class="mt-2 text-slate-500">なお、当サービスはGoogle LLC のOAuth認証を利用しています。Googleによる情報の取り扱いについては、<a href="https://policies.google.com/privacy" target="_blank" class="text-primary hover:underline">Googleのプライバシーポリシー</a>をご確認ください。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">4. 個人情報の管理</h2>
            <p>取得した個人情報は、不正アクセス・紛失・漏洩等を防止するため、適切な安全管理措置を講じます。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">5. Cookie の使用</h2>
            <p>当サービスでは、ログイン状態の維持等のためにCookieを使用しています。ブラウザの設定によりCookieを無効にすることができますが、一部のサービスが利用できなくなる場合があります。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">6. 個人情報の開示・訂正・削除</h2>
            <p>ユーザーご本人から個人情報の開示・訂正・削除のご請求があった場合は、本人確認の上、合理的な範囲で対応いたします。お問い合わせ先までご連絡ください。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">7. プライバシーポリシーの変更</h2>
            <p>本ポリシーは、必要に応じて変更することがあります。重要な変更がある場合はサービス上でお知らせします。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">8. 運営者情報・お問い合わせ</h2>
            <div class="bg-slate-50 rounded-xl p-4 space-y-1">
                <p><span class="font-bold">運営者：</span>Solvedience</p>
            </div>
        </section>

        <p class="text-xs text-slate-400">制定日：2026年3月</p>
    </div>
</div>
@endsection
