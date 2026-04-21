@extends('layouts.app')

@section('title', '要出典リスト | 管理者')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <h1 class="text-2xl font-bold text-slate-800">要出典リスト</h1>

    @if(session('success'))
    <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif

    @if($list->isEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm px-6 py-12 text-center text-slate-400 text-sm">
        要出典リストは空です
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">追加日時</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">投稿者</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500">テーマ / 項目</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500">考察・意見の内容</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">メール</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">?付与</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">操作</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($list as $entry)
                @php
                    $content = $entry->content;
                    $opinionSentences = $content?->sentences->filter(fn($s) => $s->type === \App\Models\ContentSentence::TYPE_OPINION) ?? collect();
                @endphp
                <tr class="align-top">
                    {{-- 追加日時 --}}
                    <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                        {{ $entry->created_at->format('Y/m/d H:i') }}
                    </td>

                    {{-- 投稿者 --}}
                    <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                        {{ $content?->user?->name ?? '退会済み' }}
                        @if($content?->user?->email)
                        <p class="text-slate-400">{{ $content->user->email }}</p>
                        @endif
                    </td>

                    {{-- テーマ / 項目 --}}
                    <td class="px-4 py-3 text-xs text-slate-600">
                        <p class="text-slate-400">{{ $content?->item?->theme?->name ?? '—' }}</p>
                        <p class="font-bold text-slate-700 mt-0.5">{{ $content?->item?->name ?? '—' }}</p>
                        @if($content?->item?->theme)
                        <a href="{{ route('animal_welfare.show', $content->item->theme) }}" target="_blank" rel="noopener noreferrer"
                            class="inline-block mt-1 text-xs text-blue-500 hover:underline">
                            投稿ページを確認 →
                        </a>
                        @endif
                    </td>

                    {{-- 考察・意見の内容 --}}
                    <td class="px-4 py-3 text-xs text-slate-700 max-w-sm">
                        @if($opinionSentences->isNotEmpty())
                        <ul class="space-y-1">
                            @foreach($opinionSentences as $sentence)
                            <li class="leading-relaxed">{{ $sentence->value }}</li>
                            @endforeach
                        </ul>
                        @else
                        <span class="text-slate-300">意見文なし</span>
                        @endif
                    </td>

                    {{-- メール送信状態 --}}
                    <td class="px-4 py-3 text-xs whitespace-nowrap">
                        @if($entry->mail_sent_at)
                        <span class="text-green-600 font-bold">送信済み</span>
                        <p class="text-slate-400 mt-0.5">{{ $entry->mail_sent_at->format('Y/m/d H:i') }}</p>
                        @else
                        <span class="text-slate-400">未送信</span>
                        @endif
                    </td>

                    {{-- ?付与状態 --}}
                    <td class="px-4 py-3 text-xs whitespace-nowrap">
                        @if($entry->question_mark_added_at)
                        <span class="text-blue-600 font-bold">付与済み</span>
                        <p class="text-slate-400 mt-0.5">{{ $entry->question_mark_added_at->format('Y/m/d H:i') }}</p>
                        @else
                        <span class="text-slate-400">未付与</span>
                        @endif
                    </td>

                    {{-- 操作 --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-2 min-w-[120px]">
                            {{-- メール送信 --}}
                            @if($content?->user)
                            <form method="POST"
                                action="{{ route('admin.reference_needed.send_mail', $entry) }}"
                                onsubmit="return confirm('投稿者へ出典追記依頼メールを送信しますか？')">
                                @csrf
                                <button type="submit"
                                    class="w-full px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                    メール送信
                                </button>
                            </form>
                            @else
                            <button disabled
                                class="w-full px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-lg cursor-not-allowed">
                                退会済み
                            </button>
                            @endif

                            {{-- 文末に？を追加 --}}
                            @if(!$entry->question_mark_added_at)
                            <form method="POST"
                                action="{{ route('admin.reference_needed.append_question_mark', $entry) }}"
                                onsubmit="return confirm('意見文の文末に「？」を追加しますか？\nこの操作は履歴から確認できます。')">
                                @csrf
                                <button type="submit"
                                    class="w-full px-3 py-1.5 bg-amber-500 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                    文末に？を追加
                                </button>
                            </form>
                            @endif

                            {{-- リストから削除（誤操作取り消し） --}}
                            <form method="POST"
                                action="{{ route('admin.reference_needed.destroy', $entry) }}"
                                onsubmit="return confirm('要出典リストから削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">
                                    リストから削除
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

</div>
@endsection
