@extends('layouts.app')

@section('title', '投稿内容 承認管理 | 管理者')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">

    <h1 class="text-2xl font-bold text-slate-800">投稿内容 承認管理</h1>

    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm">
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== 承認待ち（内容・項目を統合） ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">承認待ち</h2>

        @if($pendingContents->isEmpty() && $pendingItems->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm px-6 py-12 text-center text-slate-400 text-sm">
            承認待ちの投稿はありません
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">投稿日時</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">種別</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">投稿者</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">テーマ / 項目</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">内容</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">出典</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    {{-- 項目の承認待ち --}}
                    @foreach($pendingItems as $item)
                    <tr class="align-top">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $item->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-bold text-amber-500 whitespace-nowrap">
                            項目
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                            {{ $item->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">
                            <p class="text-slate-400">{{ $item->theme?->name ?? '—' }}</p>
                            <p class="font-bold text-slate-700 mt-0.5">{{ $item->name }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            カテゴリ：{{ $item->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-300">なし</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                <form method="POST" action="{{ route('admin.pending_posts.items.approve', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                        承認
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.pending_posts.items.reject', $item) }}"
                                    onsubmit="return confirm('この項目を却下しますか？\n却下後も履歴から確認できます。')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-red-100 text-red-600 text-xs font-bold rounded-lg hover:bg-red-200 transition">
                                        却下
                                    </button>
                                </form>
                                <a href="{{ route('admin.items.history', $item) }}"
                                    class="block w-full text-center px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">
                                    履歴
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 内容の承認待ち --}}
                    @foreach($pendingContents as $content)
                    <tr class="align-top">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $content->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-bold text-green-500 whitespace-nowrap">
                            内容
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                            {{ $content->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">
                            <p class="text-slate-400">{{ $content->item->theme->name ?? '—' }}</p>
                            <p class="font-bold text-slate-700 mt-0.5">{{ $content->item->name ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700 max-w-sm">
                            @foreach($content->sentences as $sentence)
                            <span>{{ $sentence->value }}</span>@if(!$loop->last) <span> </span>@endif
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-xs max-w-xs">
                            {{-- 出典URLがある文章を全て表示し、承認前にGoogleセーフブラウジングで安全確認できるようにする --}}
                            @php $urlSentences = $content->sentences->filter(fn($s) => $s->url); @endphp
                            @if($urlSentences->isNotEmpty())
                            <div class="flex flex-col gap-2">
                                @foreach($urlSentences as $sentence)
                                <div>
                                    <a href="{{ $sentence->url }}" target="_blank" rel="noopener noreferrer"
                                        class="text-blue-500 hover:underline break-all">
                                        {{ $sentence->url_title ?: $sentence->url }}
                                    </a>
                                    {{-- Googleセーフブラウジングで安全確認するボタン。承認前に悪意あるサイトでないかチェックするため --}}
                                    <a href="https://transparencyreport.google.com/safe-browsing/search?url={{ urlencode($sentence->url) }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-block mt-1 px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded text-xs hover:bg-amber-100 transition">
                                        安全確認
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-slate-300">なし</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                {{-- 意見文が含まれる場合のみ要出典ボタンを表示 --}}
                                @if($content->sentences->where('type', \App\Models\ContentSentence::TYPE_OPINION)->isNotEmpty())
                                <form method="POST"
                                    action="{{ route('admin.reference_needed.store', $content) }}"
                                    onsubmit="return confirm('この投稿を要出典リストに追加しますか？')">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-orange-100 text-orange-700 text-xs font-bold rounded-lg hover:bg-orange-200 transition">
                                        要出典
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.pending_posts.approve', $content) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                        承認
                                    </button>
                                </form>
                                {{-- 却下：削除ではなくstatusをrejectedに変更するだけ --}}
                                <form method="POST" action="{{ route('admin.pending_posts.reject', $content) }}"
                                    onsubmit="return confirm('この投稿を却下しますか？\n却下後も履歴から確認できます。')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-red-100 text-red-600 text-xs font-bold rounded-lg hover:bg-red-200 transition">
                                        却下
                                    </button>
                                </form>
                                <a href="{{ route('admin.contents.history', $content) }}"
                                    class="block w-full text-center px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">
                                    履歴
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @endif
    </section>

    {{-- ===== 却下済み履歴（内容・項目を統合） ===== --}}
    <section>
        <h2 class="text-base font-bold text-slate-700 mb-3">却下済み履歴</h2>

        @if($rejectedContents->isEmpty() && $rejectedItems->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm px-6 py-8 text-center text-slate-400 text-sm">
            却下済みの投稿はありません
        </div>
        @else
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap">投稿日時</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">種別</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">投稿者</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">テーマ / 項目</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">内容</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">

                    {{-- 項目の却下済み --}}
                    @foreach($rejectedItems as $item)
                    <tr class="align-top bg-slate-50 opacity-80">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $item->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-bold text-amber-500 whitespace-nowrap">
                            項目
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                            {{ $item->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">
                            <p class="text-slate-400">{{ $item->theme?->name ?? '—' }}</p>
                            <p class="font-bold text-slate-700 mt-0.5">{{ $item->name }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            カテゴリ：{{ $item->category?->name ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                {{-- 誤却下の場合に承認待ちに戻せるようにする --}}
                                <form method="POST" action="{{ route('admin.pending_posts.items.undo_reject', $item) }}"
                                    onsubmit="return confirm('この項目を承認待ちに戻しますか？')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-slate-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                        承認待ちに戻す
                                    </button>
                                </form>
                                <a href="{{ route('admin.items.history', $item) }}"
                                    class="block w-full text-center px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">
                                    履歴
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    {{-- 内容の却下済み --}}
                    @foreach($rejectedContents as $content)
                    <tr class="align-top bg-slate-50 opacity-80">
                        <td class="px-4 py-3 text-xs text-slate-400 whitespace-nowrap">
                            {{ $content->created_at->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-4 py-3 text-xs font-bold text-green-500 whitespace-nowrap">
                            内容
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 whitespace-nowrap">
                            {{ $content->user?->name ?? '退会済み' }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600">
                            <p class="text-slate-400">{{ $content->item->theme->name ?? '—' }}</p>
                            <p class="font-bold text-slate-700 mt-0.5">{{ $content->item->name ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700 max-w-sm">
                            @foreach($content->sentences as $sentence)
                            <span>{{ $sentence->value }}</span>@if(!$loop->last) <span> </span>@endif
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col gap-2">
                                {{-- 誤却下の場合に承認待ちに戻せるようにする --}}
                                <form method="POST" action="{{ route('admin.pending_posts.undo_reject', $content) }}"
                                    onsubmit="return confirm('この投稿を承認待ちに戻しますか？')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-full px-3 py-1.5 bg-slate-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition">
                                        承認待ちに戻す
                                    </button>
                                </form>
                                <a href="{{ route('admin.contents.history', $content) }}"
                                    class="block w-full text-center px-3 py-1.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-lg hover:bg-slate-200 transition">
                                    履歴
                                </a>
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
