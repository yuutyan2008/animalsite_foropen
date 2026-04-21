@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
    <h2 class="text-2xl font-bold mb-6 text-slate-800">違反報告口コミ一覧</h2>

    {{-- フラッシュメッセージ（古いバージョンでも動作） --}}
    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="p-4">報告日時</th>
                    <th class="p-4">内容</th>
                    <th class="p-4">理由</th>
                    <th class="p-4">処理状態</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                {{-- 対応済みの行は背景をグレーにし、透明度を下げる (opacity-60) --}}
                <tr class="border-b {{ $report->is_resolved ? 'bg-slate-100 opacity-70' : 'bg-white' }}">
                    <td class="p-4 text-sm">{{ $report->created_at->format('Y/m/d H:i') }}</td>

                    <td class="p-4 text-sm">
                        @if($report->review)
                        <div class="font-bold text-xs text-gray-500">投稿者: {{ $report->review->user->name }}</div>
                        {{ Str::limit($report->review->comment, 40) }}
                        @else
                        <span class="text-red-400">削除済み口コミ</span>
                        @endif
                    </td>

                    <td class="p-4 text-sm">
                        <div class="font-bold text-gray-800">{{ $report->reason }}</div>

                        {{-- ここに description を追加 --}}
                        @if($report->description)
                        <div class="text-xs text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">
                            {{ $report->description }}
                        </div>
                        @else
                        <div class="text-xs text-gray-400 mt-1 italic">詳細なし</div>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($report->is_resolved)
                        <span class="text-green-600 font-bold text-xs">【完了】</span>
                        {{-- ここに、過去に送ったメッセージなどを小さく表示しても良い --}}
                        @else
                        {{-- 未対応の場合のみ、処理フォームを表示 --}}
                        <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px]"><input type="checkbox" name="delete_comment"> 削除</label>
                                <textarea name="message" class="text-xs border p-1" placeholder="病院への連絡" required></textarea>
                                <button class="bg-blue-500 text-white text-xs py-1 rounded">完了にする</button>
                            </div>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ページネーション --}}
        <div class="p-4">
            {{ $reports->links() }}
        </div>
    </div>
</div>
@endsection
