{{-- 【項目追加 確認画面】ItemController::confirmShow() --}}
@extends('layouts.app')

@section('title', '項目を確認 | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">編集画面</a>
        <span class="mx-2">/</span>
        <a href="{{ route('animal_welfare.items.create', ['theme_id' => $theme->id]) }}" class="hover:text-green-600">項目を追加</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">確認</span>
    </nav>

    {{-- ステップインジケーター --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-green-100 text-green-700">1</div>
            <span class="text-sm text-slate-400">入力</span>
        </div>
        <div class="flex-1 h-px bg-slate-200"></div>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold bg-green-600 text-white">2</div>
            <span class="text-sm text-slate-700 font-bold">確認・送信</span>
        </div>
    </div>

    <h1 class="text-2xl font-bold text-slate-800 mb-2">項目の確認</h1>
    <p class="text-sm text-slate-500 mb-6">テーマ：<span class="font-bold text-slate-700">{{ $theme->name }}</span></p>

    {{-- 入力内容プレビュー --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-5 space-y-4">
        <h2 class="text-sm font-bold text-slate-600 border-b border-slate-100 pb-2">追加する項目</h2>
        @foreach($items as $item)
        @php
            $category = $theme->categories->firstWhere('id', $item['category_id']);
        @endphp
        <div class="flex items-center gap-3">
            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 shrink-0">
                {{ $category?->name ?? '不明' }}
            </span>
            <span class="text-sm text-slate-700">{{ $item['name'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- 公開フローの案内 --}}
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-5">
        <h2 class="text-sm font-bold text-blue-800 flex items-center gap-1.5 mb-3">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
            </svg>
            投稿後の流れについて
        </h2>
        <p class="text-xs text-blue-900 leading-relaxed">
            管理者の確認後の公開となります。公開の場合、通常 <span class="font-bold">1〜2日以内</span> にメールにて公開のご連絡とともに公開をさせていただきます。残念ながら公開できなかった場合も、簡易ではありますが理由とともにメールにてご連絡いたします。
        </p>
        <a href="{{ route('posting_guide') }}#flow" target="_blank" rel="noopener noreferrer"
            class="inline-block mt-2 text-xs text-blue-600 underline hover:text-blue-800">
            公開までの流れを詳しく見る →
        </a>
    </div>

    {{-- 注意事項 --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-5 space-y-3">
        <h2 class="text-sm font-bold text-amber-800 flex items-center gap-1.5">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
            投稿前の注意事項
        </h2>
        <ul class="text-xs text-amber-900 space-y-2 list-disc list-inside leading-relaxed">
            <li>虚偽・誇張・根拠のない情報の投稿はお控えください。</li>
            <li>個人や団体を誹謗中傷する内容、差別的表現、過度に感情的な表現は掲載できません。</li>
            <li>投稿された内容は<a href="{{ route('privacy') }}" target="_blank" class="underline hover:text-amber-700">プライバシーポリシー</a>および利用規約に基づいて取り扱われます。</li>
            <li>不適切と判断された投稿は予告なく非公開・削除される場合があります。</li>
        </ul>
    </div>

    {{-- プライバシーポリシー同意 --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 mb-6" x-data="{ agreed: false }">
        <label class="flex items-start gap-3 cursor-pointer mb-5">
            <input type="checkbox" x-model="agreed" class="mt-0.5 w-4 h-4 accent-green-600 shrink-0">
            <span class="text-sm text-slate-700 leading-relaxed">
                上記の注意事項を確認し、
                <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer"
                    class="text-green-600 underline hover:text-green-700">プライバシーポリシー</a>
                に同意した上で投稿します。
            </span>
        </label>

        {{-- 送信フォーム --}}
        <form method="POST" action="{{ route('animal_welfare.items.store') }}">
            @csrf
            <input type="hidden" name="theme_id" value="{{ $theme->id }}">
            @foreach($items as $index => $item)
            <input type="hidden" name="items[{{ $index }}][name]" value="{{ $item['name'] }}">
            <input type="hidden" name="items[{{ $index }}][category_id]" value="{{ $item['category_id'] }}">
            @endforeach

            <div class="flex justify-between gap-3">
                <a href="{{ route('animal_welfare.items.create', ['theme_id' => $theme->id]) }}"
                    class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                    ← 入力に戻る
                </a>
                <button type="submit"
                    :disabled="!agreed"
                    :class="agreed ? 'bg-green-600 hover:opacity-90 cursor-pointer' : 'bg-slate-300 cursor-not-allowed'"
                    class="px-6 py-2 text-white text-sm font-bold rounded-xl transition">
                    投稿する
                </button>
            </div>
            <p class="text-xs text-slate-400 text-right mt-2" x-show="!agreed">同意チェックを入れると投稿ボタンが有効になります</p>
        </form>
    </div>

</div>
@endsection
