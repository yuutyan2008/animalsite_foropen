@extends('layouts.app')

@section('title', 'お問い合わせ確認')

@section('content')
<div class="max-w-lg mx-auto mt-12">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm p-8">

        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">お問い合わせ内容の確認</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            以下の内容で送信します。よろしければ「送信する」を押してください。
        </p>

        <dl class="space-y-4 text-sm mb-8">
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3">
                <dt class="font-bold text-slate-500 dark:text-slate-400 mb-1">お名前</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $data['name'] }}</dd>
            </div>
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3">
                <dt class="font-bold text-slate-500 dark:text-slate-400 mb-1">メールアドレス</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $data['email'] }}</dd>
            </div>
            <div class="border-b border-slate-100 dark:border-slate-700 pb-3">
                <dt class="font-bold text-slate-500 dark:text-slate-400 mb-1">件名</dt>
                <dd class="text-slate-800 dark:text-slate-100">{{ $data['subject'] }}</dd>
            </div>
            <div>
                <dt class="font-bold text-slate-500 dark:text-slate-400 mb-1">お問い合わせ内容</dt>
                <dd class="text-slate-800 dark:text-slate-100 whitespace-pre-wrap">{{ $data['body'] }}</dd>
            </div>
        </dl>

        <div class="flex gap-3">
            {{-- 戻る（入力画面へ。セッションはそのまま残す） --}}
            <a href="{{ route('contact.create') }}"
                class="flex-1 text-center px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                戻る
            </a>

            {{-- 送信 --}}
            <form method="POST" action="{{ route('contact.store') }}" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition text-sm">
                    送信する
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
