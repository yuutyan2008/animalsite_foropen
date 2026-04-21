{{-- 【項目修正】既存項目の名前・カテゴリをテーマ単位でまとめて編集するフォーム → ItemController::updateAll() --}}
@extends('layouts.app')

@section('title', '項目を修正 | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <a href="{{ route('admin.animal_welfare.edit') }}" class="hover:text-green-600">修正する</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">項目を修正</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">項目を修正</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    @forelse($themes as $theme)
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-4">

        {{-- テーマ名の見出し --}}
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">{{ $theme->name }}</p>

        @if($theme->items->isEmpty())
        <p class="text-sm text-slate-400 italic">このテーマには項目がありません。</p>
        @else

        {{--
            テーマ単位で1つのフォームにまとめる。
            フォームを送信すると POST /admin/items/batch-update に届く。
            各項目の入力欄の name を items[項目のID] とすることで、
            コントローラーでは $request->items = ['1' => '名前A', '3' => '名前B'] のように受け取れる。
        --}}
        <form method="POST" action="{{ route('admin.items.updateAll') }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">

            {{-- テーブルヘッダー --}}
            <div class="grid grid-cols-2 gap-3 mb-2 px-1">
                <p class="text-xs font-bold text-slate-500">項目名</p>
                <p class="text-xs font-bold text-slate-500">カテゴリ</p>
            </div>

            <div class="space-y-2 mb-4">
                @foreach($theme->items as $item)
                <div class="grid grid-cols-2 gap-3">

                    {{-- 項目名の入力欄 --}}
                    {{-- name="items_name[項目ID]" で送信する --}}
                    <input
                        type="text"
                        name="items_name[{{ $item->id }}]"
                        value="{{ old('items_name.' . $item->id, $item->name) }}"
                        maxlength="100"
                        required
                        class="border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">

                    {{-- カテゴリの選択ドロップダウン --}}
                    {{-- name="items_category[項目ID]" で送信する --}}
                    {{-- このテーマに属するカテゴリ一覧を選択肢に表示する --}}
                    <select
                        name="items_category[{{ $item->id }}]"
                        class="border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 bg-white">
                        <option value="">-- カテゴリなし --</option>
                        @foreach($theme->categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('items_category.' . $item->id, $item->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>

                </div>
                @endforeach
            </div>

            {{-- テーマ内の全項目をまとめて保存するボタン --}}
            <div class="flex justify-end">
                <button type="submit"
                    class="px-5 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                    まとめて保存
                </button>
            </div>

        </form>
        @endif

    </div>
    @empty
    <p class="text-sm text-slate-400">テーマがありません。</p>
    @endforelse

    <div class="mt-2">
        <a href="{{ route('admin.animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
    </div>

</div>
@endsection
