@extends('layouts.app')

@section('title', '違反報告一覧 | 管理者')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    <h1 class="text-2xl font-bold text-slate-800">違反報告一覧</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== 未対応 ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">未対応</h2>

        @if($unresolvedReports->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-8 text-center text-slate-400 text-sm">
            未対応の違反報告はありません
        </div>
        @else
        <div class="space-y-4">
            @foreach($unresolvedReports as $report)
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 space-y-4">

                {{-- 報告の概要 --}}
                <div class="flex flex-wrap gap-4 text-xs text-slate-400">
                    <span>{{ $report->created_at->format('Y/m/d H:i') }}</span>
                    <span>報告者：{{ $report->user?->name ?? '退会済み' }}</span>
                    @if($report->theme_id)
                    <span class="text-purple-500 font-bold">テーマ</span>
                    @elseif($report->item_id)
                    <span class="text-amber-500 font-bold">項目</span>
                    @else
                    <span class="text-green-500 font-bold">内容</span>
                    @endif
                </div>

                {{-- 報告理由 --}}
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-1">報告理由</p>
                    <p class="text-sm font-bold text-slate-700">{{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}</p>
                    @if($report->other_detail)
                    <p class="text-xs text-slate-400 mt-0.5">{{ $report->other_detail }}</p>
                    @endif
                </div>

                {{-- 報告対象の内容 + 投稿ページへのリンク --}}
                <div>
                    <p class="text-xs font-bold text-slate-500 mb-1">報告対象</p>
                    @if($report->theme_id)
                        @if($report->theme)
                        <p class="text-sm font-semibold text-slate-700">{{ $report->theme->name }}</p>
                        {{-- テーマページに別タブで飛んで内容を確認できるリンク --}}
                        <a href="{{ route('animal_welfare.show', $report->theme) }}" target="_blank" rel="noopener noreferrer"
                            class="inline-block mt-1 text-xs text-blue-500 hover:underline">
                            投稿ページを確認する →
                        </a>
                        @else
                        <span class="text-xs text-slate-300">削除済み</span>
                        @endif

                    @elseif($report->item_id)
                        @if($report->item)
                        <p class="text-sm font-semibold text-slate-700">{{ $report->item->name }}</p>
                        <p class="text-xs text-slate-400">テーマ：{{ $report->item->theme?->name ?? '—' }}</p>
                        {{-- 項目が属するテーマページに別タブで飛んで内容を確認できるリンク --}}
                        @if($report->item->theme)
                        <a href="{{ route('animal_welfare.show', $report->item->theme) . '#item-' . $report->item->id }}" target="_blank" rel="noopener noreferrer"
                            class="inline-block mt-1 text-xs text-blue-500 hover:underline">
                            投稿ページを確認する →
                        </a>
                        @endif
                        <a href="{{ route('admin.animal_welfare.items.edit') }}?redirect_to=reports" target="_blank" rel="noopener noreferrer"
                            class="inline-block mt-1 text-xs text-amber-500 hover:underline">
                            項目編集画面を開く →
                        </a>
                        @else
                        <span class="text-xs text-slate-300">削除済み</span>
                        @endif

                    @else
                        @if($report->content)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 space-y-1">
                            @if($report->content->title)
                            <p class="font-bold">{{ $report->content->title }}</p>
                            @endif
                            <p>{{ Str::limit($report->content->sentences->pluck('value')->join(' '), 150) }}</p>
                            @php $firstSentence = $report->content->sentences->first(); @endphp
                            @if($firstSentence?->url)
                            <p class="text-xs">
                                出典：<a href="{{ $firstSentence->url }}" target="_blank" rel="noopener noreferrer"
                                    class="text-blue-400 hover:underline break-all">
                                    {{ $firstSentence->url_title ?: $firstSentence->url }}
                                </a>
                            </p>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mt-1">テーマ：{{ $report->content->item?->theme?->name ?? '—' }} / 項目：{{ $report->content->item?->name ?? '—' }}</p>
                        {{-- 投稿が掲載されているテーマページに別タブで飛んで実際の掲載状態を確認できるリンク --}}
                        @if($report->content->item?->theme)
                        <a href="{{ route('animal_welfare.show', $report->content->item->theme) . '#item-' . $report->content->item->id }}" target="_blank" rel="noopener noreferrer"
                            class="inline-block mt-1 text-xs text-blue-500 hover:underline">
                            投稿ページを確認する →
                        </a>
                        @endif
                        @else
                        <span class="text-xs text-slate-300">削除済み</span>
                        @endif
                    @endif
                </div>

                {{-- 操作：掲載継続 or 削除 --}}
                <div class="border-t border-slate-100 pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- 掲載継続：報告ユーザーにメール通知 --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full px-4 py-2 bg-slate-600 text-white text-xs font-bold rounded-xl hover:opacity-90 transition">
                            掲載継続・報告ユーザーに通知する
                        </button>
                        <div x-show="open" x-cloak class="mt-3 space-y-2">
                            <form method="POST" action="{{ route('admin.social_issues.reports.keep', $report) }}"
                                onsubmit="return confirm('掲載を継続し、報告ユーザーにメールを送信しますか？')">
                                @csrf
                                <textarea name="admin_comment" rows="3" maxlength="500"
                                    placeholder="報告ユーザーへのコメント（任意・500字以内）"
                                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-slate-300"></textarea>
                                <button type="submit"
                                    class="mt-2 w-full px-4 py-1.5 bg-slate-700 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                    送信する
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 削除：投稿ユーザー・報告ユーザー両方にメール通知（contentのみ・削除済みは除く） --}}
                    @if($report->content && !$report->content->trashed())
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full px-4 py-2 bg-red-500 text-white text-xs font-bold rounded-xl hover:opacity-90 transition">
                            削除・両ユーザーに通知する
                        </button>
                        <div x-show="open" x-cloak class="mt-3 space-y-2">
                            <form method="POST" action="{{ route('admin.social_issues.reports.delete', $report) }}"
                                onsubmit="return confirm('この内容を削除しますか？\n投稿ユーザーと報告ユーザーにメールが送信されます。')">
                                @csrf
                                {{-- 削除理由：投稿ガイドの判断基準から選択 --}}
                                <p class="text-xs font-bold text-slate-600 mb-1">削除理由 <span class="text-red-500">*</span></p>
                                <div class="space-y-1 mb-2">
                                    @foreach(\App\Models\Report::REASONS as $value => $label)
                                    <label class="flex items-start gap-2 text-xs text-slate-600 cursor-pointer">
                                        <input type="radio" name="delete_reason" value="{{ $value }}" required
                                            class="accent-red-400 mt-0.5 shrink-0">
                                        <span>{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                <textarea name="admin_comment" rows="3" maxlength="500"
                                    placeholder="両ユーザーへのコメント（任意・500字以内）"
                                    class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-red-300"></textarea>
                                <button type="submit"
                                    class="mt-2 w-full px-4 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                    削除して送信する
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            @endforeach
        </div>
        @endif
    </section>

    {{-- ===== 対応済み履歴 ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">対応済み履歴</h2>

        @if($resolvedReports->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl px-6 py-8 text-center text-slate-400 text-sm">
            対応済みの違反報告はありません
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">報告日時</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">種別</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">対象</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">報告理由</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">報告者</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($resolvedReports as $report)
                    <tr class="bg-slate-50 opacity-70 align-top">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $report->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-bold whitespace-nowrap">
                            @if($report->theme_id)
                            <span class="text-purple-500">テーマ</span>
                            @elseif($report->item_id)
                            <span class="text-amber-500">項目</span>
                            @else
                            <span class="text-green-500">内容</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700 max-w-xs">
                            @if($report->theme_id)
                                @if($report->theme)
                                <p class="font-semibold">{{ $report->theme->name }}</p>
                                @else
                                <span class="text-slate-300">削除済み</span>
                                @endif
                            @elseif($report->item_id)
                                @if($report->item)
                                <p class="font-semibold">{{ $report->item->name }}</p>
                                @else
                                <span class="text-slate-300">削除済み</span>
                                @endif
                            @else
                                @if($report->content)
                                @if($report->content->title)
                                <p class="font-semibold text-slate-700 mb-0.5">{{ $report->content->title }}</p>
                                @endif
                                <p>{{ Str::limit($report->content->sentences->pluck('value')->join(' '), 100) }}</p>
                                @if($report->content->trashed())
                                <span class="inline-block mt-1 px-1.5 py-0.5 bg-red-100 text-red-500 text-xs rounded font-bold">削除済み</span>
                                @endif
                                @else
                                <span class="text-slate-300">削除済み</span>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs font-bold text-slate-700">
                            {{ \App\Models\Report::REASONS[$report->reason] ?? $report->reason }}
                            @if($report->other_detail)
                            <p class="font-normal text-slate-400 mt-0.5">{{ $report->other_detail }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ $report->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 space-y-2">
                            {{-- 誤操作時に未対応に戻す（メール通知なし） --}}
                            <form method="POST" action="{{ route('admin.social_issues.reports.unresolve', $report) }}"
                                onsubmit="return confirm('この報告を未対応に戻しますか？\nメール通知は行われません。')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 bg-slate-200 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-300 transition whitespace-nowrap">
                                    未対応に戻す
                                </button>
                            </form>
                            {{-- 削除済み内容の復元 --}}
                            @if(!$report->theme_id && !$report->item_id && $report->content && $report->content->trashed())
                            <form method="POST" action="{{ route('admin.pending_posts.restore', $report->content->id) }}"
                                onsubmit="return confirm('この内容を復元しますか？')">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg hover:bg-amber-200 transition whitespace-nowrap">
                                    内容を復元する
                                </button>
                            </form>
                            @endif
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
