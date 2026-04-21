{{-- 管理者が削除内容を一覧で確認する --}}
@extends('layouts.app')

@section('title', '削除済み一覧 | 管理者')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">

    <h1 class="text-2xl font-bold text-slate-800">削除済み一覧</h1>

    @if(session('success'))
    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== 削除済み項目 ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">削除済み項目</h2>

        @if($deletedItems->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-8 text-center text-slate-400 text-sm">
            削除済みの項目はありません
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">削除日時</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">テーマ</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">項目名</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">カテゴリ</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($deletedItems as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $item->deleted_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $item->theme?->name ?? '―' }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->name }}</td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $item->category?->name ?? '―' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('admin.items.history', $item->id) }}"
                                    class="px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                                    履歴を見る
                                </a>
                                <form method="POST" action="{{ route('admin.items.restore', $item->id) }}"
                                    onsubmit="return confirm('「{{ $item->name }}」を復元しますか？')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-bold bg-green-600 text-white rounded-lg hover:opacity-90 transition">
                                        復元
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>

    {{-- ===== 削除済み内容 ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">削除済み内容</h2>

        @if($deletedContents->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-8 text-center text-slate-400 text-sm">
            削除済みの内容はありません
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">削除日時</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">テーマ / 項目</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">投稿者</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 text-left">内容（抜粋）</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($deletedContents as $content)
                    <tr class="hover:bg-slate-50 transition align-top">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $content->deleted_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            <p class="text-slate-400">{{ $content->item?->theme?->name ?? '―' }}</p>
                            <p class="font-bold text-slate-700 mt-0.5">{{ $content->item?->name ?? '―' }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                            {{ $content->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700 max-w-xs">
                            @if($content->title)
                            <p class="font-bold mb-0.5">{{ $content->title }}</p>
                            @endif
                            {{-- histories から削除直前のスナップショットを取得して表示 --}}
                            @php
                            $lastHistory = $content->histories()->where('action', 'deleted')->latest('history_number')->first();
                            @endphp
                            @if($lastHistory)
                            {{ Str::limit(collect($lastHistory->sentences)->pluck('value')->implode(' '), 80) }}
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('admin.contents.history', $content->id) }}"
                                    class="px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition">
                                    履歴を見る
                                </a>
                                <form method="POST" action="{{ route('admin.pending_posts.restore', $content->id) }}"
                                    onsubmit="return confirm('この内容を復元しますか？')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 text-xs font-bold bg-green-600 text-white rounded-lg hover:opacity-90 transition">
                                        復元
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </section>

</div>
@endsection
