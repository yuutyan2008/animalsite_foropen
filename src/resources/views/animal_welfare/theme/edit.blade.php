{{-- 【テーマ修正】既存テーマの名前を編集するフォーム → ThemeController::update() --}}
@extends('layouts.app')

@section('title', 'テーマを修正 | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.animal_welfare.edit') }}" class="hover:text-green-600">修正する</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">テーマを修正</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">テーマを修正</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 space-y-4">

        @forelse($themes as $theme)
        {{-- テーマごとに独立したフォームを持つ --}}
        <form method="POST" action="{{ route('admin.animal_welfare.themes.update', $theme) }}">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-3">
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $theme->name) }}"
                    maxlength="100"
                    required
                    class="flex-1 border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                <button type="submit"
                    class="px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition whitespace-nowrap">
                    保存
                </button>
            </div>

            @error('name')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </form>
        @empty
        <p class="text-sm text-slate-400">テーマがありません。</p>
        @endforelse

    </div>

    <div class="mt-4">
        <a href="{{ route('admin.animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
    </div>

</div>
@endsection
