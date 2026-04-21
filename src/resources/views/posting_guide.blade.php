@extends('layouts.app')

@section('title', '投稿について | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto py-8">

    <h1 class="text-2xl font-bold text-slate-800 mb-8">投稿について</h1>

    {{-- 目次 --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-8 text-sm space-y-2">
        <p class="text-xs font-bold text-slate-500 mb-3">目次</p>
        <a href="#features" class="block text-green-600 hover:underline">1. 投稿の方法</a>
        <a href="#flow" class="block text-green-600 hover:underline">2. 公開までの流れ</a>
        <a href="#criteria" class="block text-green-600 hover:underline">3. 公開・非公開の判断基準</a>
        <a href="#caution" class="block text-green-600 hover:underline">4. 投稿の際のご注意</a>
    </div>

    {{-- 1. 投稿の方法 --}}
    <section id="features" class="mb-10 scroll-mt-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-green-500 rounded-full inline-block"></span>
            1. 投稿の方法
        </h2>
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <ul class="text-sm text-slate-700 space-y-3 list-disc list-inside leading-relaxed">
                <li>（内容未定）</li>
                <li>（内容未定）</li>
                <li>（内容未定）</li>
            </ul>
        </div>
    </section>

    {{-- 1. 公開までの流れ --}}
    <section id="flow" class="mb-10 scroll-mt-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-green-500 rounded-full inline-block"></span>
            1. 公開までの流れ
        </h2>
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4">

            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">1</div>
                    <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                </div>
                <div class="pb-5">
                    <p class="text-sm font-bold text-slate-700">投稿</p>
                    <p class="text-xs text-slate-500 mt-1">フォームから内容を送信してください。</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">2</div>
                    <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                </div>
                <div class="pb-5">
                    <p class="text-sm font-bold text-slate-700">管理者による確認</p>
                    <p class="text-xs text-slate-500 mt-1">通常 <span class="font-semibold text-slate-700">1〜2日以内</span> に確認します。</p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">3</div>
                    <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                </div>
                <div class="pb-5">
                    <p class="text-sm font-bold text-slate-700">公開 / 非公開の判断</p>
                    <p class="text-xs text-slate-500 mt-1">
                        <a href="#criteria" class="text-green-600 underline hover:text-green-700">判断基準</a>に沿っているかを確認し、公開・非公開を判断します。
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">4</div>
                    <div class="w-px flex-1 bg-slate-200 mt-1"></div>
                </div>
                <div class="pb-5">
                    <p class="text-sm font-bold text-slate-700">メールにてご連絡</p>
                    <p class="text-xs text-slate-500 mt-1">
                        公開の場合はその旨をメールでお知らせします。<br>
                        残念ながら公開できなかった場合も、簡易ではありますが理由とともにメールにてご連絡いたします。
                    </p>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="flex flex-col items-center">
                    <div class="w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold shrink-0">5</div>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-700">サイトに反映</p>
                    <p class="text-xs text-slate-500 mt-1">公開と判断された投稿がサイトに掲載されます。</p>
                </div>
            </div>

        </div>
    </section>

    {{-- 2. 公開・非公開の判断基準 --}}
    <section id="criteria" class="mb-10 scroll-mt-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-green-500 rounded-full inline-block"></span>
            2. 公開・非公開の判断基準
        </h2>
        <div class="bg-white border border-slate-200 rounded-2xl p-6 space-y-4 text-sm text-slate-700">
            <p class="text-xs text-slate-500">以下のいずれかに該当する場合、非公開とさせていただきます。</p>
            <ul class="space-y-3 list-disc list-inside leading-relaxed">
                <li>他のサイトを参考にしていて、出典（URL）が添付されていない</li>
                <li>個人情報を含む</li>
                <li>虚偽・誇張・根拠のない情報が含まれている</li>
                <li>特定の団体・業種を名指しで批判するもの（新聞・公式資料など客観的な情報源に基づく内容を除く）</li>
                <li>道徳や動物愛護の精神に反すると思われるもの</li>
                <li>当サービスの趣旨と無関係と思われるもの、その他ユーザーの皆様が不快に感じると思われる内容</li>
            </ul>
        </div>
    </section>

    {{-- 3. 投稿の際のご注意 --}}
    <section id="caution" class="mb-10 scroll-mt-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-5 bg-amber-500 rounded-full inline-block"></span>
            3. 投稿の際のご注意
        </h2>
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <ul class="text-sm text-slate-700 space-y-3 list-disc list-inside leading-relaxed">
                <li>誤字脱字の修正や読みやすさのための補足など、軽微な編集を行う場合があります。内容の趣旨・事実関係は変更しません。</li>
                <li>投稿にはGoogleアカウントが必要ですが、アカウント名が公表されることはありません。</li>
                <li>投稿された内容は<a href="{{ route('privacy') }}" class="text-green-600 underline hover:text-green-700">プライバシーポリシー</a>および利用規約に基づいて取り扱われます。</li>
                <li>違反報告と管理者の確認の結果、<a href="#criteria" class="text-green-600 underline hover:text-green-700">公開・非公開の判断基準</a>に反すると判断された投稿は、公開後であっても削除する場合があります。</li>
            </ul>
        </div>
    </section>

    <div class="mt-4">
        <a href="{{ route('animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← ホームに戻る</a>
    </div>

</div>
@endsection
