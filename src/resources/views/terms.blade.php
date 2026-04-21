@extends('layouts.app')

@section('title', '利用規約')

@section('content')
<div class="max-w-3xl mx-auto py-12">
    <h1 class="text-3xl font-bold text-slate-800 mb-8">利用規約</h1>

    <div class="prose prose-slate max-w-none space-y-8 text-slate-700 text-sm leading-relaxed">

        <p>本利用規約（以下「本規約」）は、「動物のための情報サイト」（以下「当サービス」）の利用条件を定めるものです。当サービスをご利用いただく前に、本規約をよくお読みください。ご利用をもって本規約にご同意いただいたものとみなします。</p>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">1. サービスの目的</h2>
            <p>当サービスは、愛玩動物に関する社会問題について、ユーザー間で情報を共有し理解を深められる場を提供することを目的としています。動物とともに暮らすすべての人の生活の質の向上に貢献することを目指しています。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">2. 掲載情報について</h2>
            <p>当サービスに掲載されている情報は、ユーザーの皆さまによる投稿を含みます。運営者は情報の正確性・完全性を保証するものではありません。情報の利用にあたっては、ご自身の判断と責任においてお取り扱いください。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">3. 投稿について</h2>
            <p>当サービスでは、動物愛護に関する情報や疑問を投稿することができます。投稿内容については、サービスの品質を維持し、すべてのユーザーが快適にご利用いただけるよう、運営者が確認・管理させていただく場合があります。あらかじめご了承ください。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">4. 禁止事項</h2>
            <p>当サービスをより多くの方に安心してご利用いただくため、誠に恐れ入りますが以下の内容を含む投稿はお控えくださいますようお願いいたします。</p>
            <ul class="list-disc list-inside space-y-2 mt-3">
                <li>個人情報を含むもの</li>
                <li>特定の個人・団体に対する誹謗中傷にあたるもの</li>
                <li>特定の団体・業種を名指しで批判するもの（新聞・公式資料など客観的な情報源に基づく内容を除く）</li>
                <li>道徳や動物愛護の精神に反すると思われるもの</li>
                <li>当サービスの趣旨と無関係と思われるもの、その他ユーザーの皆さまが不快に感じると思われる内容</li>
            </ul>
            <p class="mt-3 text-slate-500">上記に該当する投稿は、運営者の判断により削除させていただく場合があります。ご理解・ご協力をお願いいたします。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">5. アカウントの停止について</h2>
            <p>大変心苦しいことではありますが、上記の禁止事項に繰り返し違反される場合や、明らかに悪意のある行為が確認された場合には、他のユーザーの皆さまの快適な利用環境を守るため、やむを得ずアカウントの利用を停止させていただく場合があります。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">6. 免責事項</h2>
            <p>当サービスは以下について責任を負いません。</p>
            <ul class="list-disc list-inside space-y-1 mt-2">
                <li>掲載情報の正確性・最新性に起因するトラブル</li>
                <li>当サービスの利用により生じた損害</li>
                <li>システム障害・メンテナンスによるサービス停止</li>
                <li>ユーザー間のトラブル</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">7. 会員登録・退会</h2>
            <p>会員登録は無料です。退会をご希望の場合は、メニュー内の「退会する」よりお手続きください。退会後はご登録情報を削除いたします。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">8. サービスの変更・停止</h2>
            <p>運営者は、ユーザーの皆さまへの事前通知なく、当サービスの内容変更・停止・終了を行う場合があります。</p>
        </section>

        <section>
            <h2 class="text-lg font-bold text-slate-800 mb-3">10. 運営者情報</h2>
            <div class="bg-slate-50 rounded-xl p-4 space-y-1">
                <p><span class="font-bold">運営：</span>動物のための情報サイト</p>
                <p><span class="font-bold">お問い合わせ：</span><a href="{{ route('contact.create') }}" class="text-primary hover:underline">お問い合わせフォーム</a></p>
            </div>
        </section>

        <p class="text-xs text-slate-400">制定日：2026年3月　最終更新：2026年4月</p>
    </div>
</div>
@endsection
