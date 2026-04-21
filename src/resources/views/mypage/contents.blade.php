@extends('layouts.app')

@section('title', '自分の内容一覧')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">自分の内容一覧</h1>

    @if($contents->isEmpty())
    <p class="text-slate-400 text-sm">投稿した内容はありません。</p>
    @else
    <div class="border border-slate-200 dark:border-slate-700 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                <tr>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500">内容</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 w-32">テーマ</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 w-32">項目</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 w-24">状態</th>
                    <th class="px-4 py-3 text-xs font-bold text-slate-500 w-28">投稿日</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($contents as $content)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 align-top">
                    <td class="px-4 py-3 text-slate-700 dark:text-slate-300">
                        {{ Str::limit($content->sentences->first()?->value ?? '', 80) }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">
                        {{ $content->item?->theme?->name ?? '―' }}
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-600 dark:text-slate-400">
                        {{ $content->item?->name ?? '―' }}
                    </td>
                    <td class="px-4 py-3">
                        @if($content->status === 'approved')
                            <span class="text-xs text-green-600 font-bold">承認済み</span>
                        @elseif($content->status === 'rejected')
                            <span class="text-xs text-red-400 font-bold">却下</span>
                        @else
                            <span class="text-xs text-slate-400">審査中</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-400">
                        {{ $content->created_at->format('Y/m/d') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
