@extends('layouts.app')

@section('title', $theme->name . ' | Solvedience')
@section('description', $theme->name . 'に関する社会問題の背景・データ・対策を出典付きでまとめています。')

@section('content')
<div class="flex gap-8 max-w-6xl mx-auto">

    {{-- 左サイドバー：全テーマ・項目一覧（$currentTheme を渡して現在のテーマをハイライト） --}}
    @include('layouts.partials.themes_sidebar', ['currentTheme' => $theme])

    {{-- メインコンテンツ --}}
    <div class="flex-1 min-w-0">

        {{-- パンくずリスト --}}
        <nav class="text-xs text-slate-400 mb-4">
            <a href="{{ route('home') }}" class="hover:text-green-600">ホーム</a>
            <span class="mx-2">/</span>
            <span class="text-slate-700">{{ $theme->name }}</span>
        </nav>

        {{-- テーマ名 + 管理者メニュー --}}
        <div class="mb-8 flex items-start justify-between">
            <h1 class="text-2xl font-bold text-slate-800">{{ $theme->name }}</h1>
            @auth
            <div x-data="{ open: false }" class="relative ml-4">
                <button @click="open = !open" @click.outside="open = false"
                    class="text-xs text-slate-400 hover:text-slate-600 border border-slate-200 rounded-lg px-2 py-1">
                    管理
                </button>
                <div x-show="open" x-cloak
                    class="absolute right-0 top-7 z-20 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-32">
                    <a href="{{ route('animal_welfare.items.create', ['theme_id' => $theme->id]) }}"
                        class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">項目を追加</a>
                    @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
                    <a href="{{ route('admin.animal_welfare.edit.theme') }}"
                        class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">テーマを修正</a>
                    <form method="POST" action="{{ route('admin.animal_welfare.themes.destroy', $theme) }}"
                        onsubmit="return confirm('「{{ $theme->name }}」を削除しますか？')">
                        @csrf @method('DELETE')
                        <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50">削除</button>
                    </form>
                    @endif
                </div>
            </div>
            @endauth
        </div>

        {{-- 項目・内容 --}}
        @if($theme->items->isNotEmpty())
        <div class="space-y-5">
            @foreach($theme->items as $item)
            @include('animal_welfare.partials.items.item_index', ['item' => $item])
            @endforeach
        </div>
        @else
        <p class="text-slate-400 text-sm">まだ内容が登録されていません。</p>
        @endif


    </div>
</div>
@endsection
