{{-- 【テーマ追加】テーマ名を入力して新規テーマを作成するフォーム → ThemeController::store() --}}
@extends('layouts.app')

@section('title', 'テーマを追加 | Solvedience')

@section('content')
<div class="max-w-xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">テーマを追加</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">テーマを追加</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-6">

        {{-- 既存のテーマ一覧（参考表示） --}}
        @if($themes->isNotEmpty())
        <div class="mb-5 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
            <p class="text-xs font-bold text-slate-500 mb-2">既存のテーマ一覧</p>
            <ul class="space-y-1">
                @foreach($themes as $theme)
                <li class="text-xs text-slate-600">・{{ $theme->name }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- テーマ名入力フォーム --}}
        <form method="POST" action="{{ route('admin.animal_welfare.themes.store') }}">
            @csrf

            <label class="block text-sm font-bold text-slate-700 mb-2">テーマ名</label>
            <input type="text" name="name" value="{{ old('name') }}"
                maxlength="100" required
                placeholder="テーマ名（例：野良猫問題）"
                class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 mb-1">
            @error('name')
            <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror

            <div class="flex justify-between items-center mt-4">
                <a href="{{ route('animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                    追加する
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
