{{-- 【カテゴリ管理】テーマごとのカテゴリ一覧表示・追加・削除を行う画面 → CategoryController --}}
@extends('layouts.app')

@section('title', 'カテゴリ管理 | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">カテゴリ管理</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">カテゴリ管理</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @forelse($themes as $theme)
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-4">

        {{-- テーマ名 --}}
        <p class="text-sm font-bold text-slate-700 mb-3">{{ $theme->name }}</p>

        {{-- 既存カテゴリ一覧 --}}
        @if($theme->categories->isNotEmpty())
        <div class="space-y-2 mb-4">
            @foreach($theme->categories as $category)
            <div class="flex items-center gap-3">

                {{-- カテゴリ名 --}}
                <span class="flex-1 text-sm text-slate-700">{{ $category->name }}</span>

                {{-- 削除ボタン --}}
                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                    onsubmit="return confirm('「{{ $category->name }}」を削除しますか？\nこのカテゴリに属する項目のカテゴリはなしになります。')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-3 py-1.5 bg-red-100 text-red-500 text-xs font-bold rounded-lg hover:bg-red-200 transition whitespace-nowrap">
                        削除
                    </button>
                </form>

            </div>
            @endforeach
        </div>
        @else
        <p class="text-xs text-slate-400 italic mb-4">まだカテゴリがありません</p>
        @endif

        {{-- 新規カテゴリ追加フォーム --}}
        <form method="POST" action="{{ route('admin.categories.store') }}" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="theme_id" value="{{ $theme->id }}">
            <input type="text" name="name" placeholder="新しいカテゴリ名（例：現状・データ）"
                maxlength="100" required
                class="flex-1 border border-slate-300 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            <button type="submit"
                class="px-3 py-1.5 bg-green-600 text-white text-xs font-bold rounded-lg hover:opacity-90 transition whitespace-nowrap">
                追加
            </button>
        </form>

    </div>
    @empty
    <p class="text-sm text-slate-400">テーマがありません。</p>
    @endforelse

</div>
@endsection
