{{-- 【項目追加】テーマ選択 → カテゴリ・項目名を入力して新規項目を作成するフォーム → ItemController::store() --}}
@extends('layouts.app')

@section('title', '項目を追加 | Solvedience')

@section('content')
<div class="max-w-xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">項目を追加</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">項目を追加</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

        {{--
            ステップ1: テーマを選択する
            テーマを選択すると GET でページをリロードし、
            そのテーマのカテゴリ一覧と既存項目をサーバーから取得して表示する
        --}}
        <form method="GET" action="{{ route('animal_welfare.items.create') }}" class="mb-6">
            <label class="block text-sm font-bold text-slate-700 mb-2">テーマを選択</label>
            <select name="theme_id" onchange="this.form.submit()"
                class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">
                <option value="">-- テーマを選択してください --</option>
                @foreach($themes as $theme)
                <option value="{{ $theme->id }}"
                    {{ request('theme_id') == $theme->id ? 'selected' : '' }}>
                    {{ $theme->name }}
                </option>
                @endforeach
            </select>
        </form>

        @if($selectedTheme)

        {{-- このテーマの既存項目一覧（参考表示） --}}
        @if($selectedTheme->items->isNotEmpty())
        <div class="mb-5 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
            <p class="text-xs font-bold text-slate-500 mb-2">「{{ $selectedTheme->name }}」の既存項目</p>
            <ul class="space-y-1">
                @foreach($selectedTheme->items as $item)
                <li class="text-xs text-slate-600">
                    ・{{ $item->name }}
                    @if($item->category)
                    <span class="text-slate-400">（{{ $item->category->name }}）</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        @else
        <div class="mb-5 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
            <p class="text-xs text-slate-400 italic">まだ項目がありません</p>
        </div>
        @endif

        {{--
            ステップ2: カテゴリと項目名を入力して送信する
            POST /admin/items → ItemController::store()
        --}}
        @if($selectedTheme->categories->isNotEmpty())

        <form method="POST" action="{{ route('animal_welfare.items.confirm') }}" id="item-form">
            @csrf
            <input type="hidden" name="theme_id" value="{{ $selectedTheme->id }}">

            {{-- バリデーションエラー --}}
            @if($errors->any())
            <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-2">
                <label class="block text-sm font-bold text-slate-700 mb-1">カテゴリ / 項目名 <span class="text-red-500">*</span></label>
            </div>

            {{-- 項目行リスト --}}
            <div id="item-rows" class="space-y-3 mb-4">
                {{-- 1行目（静的HTML） --}}
                <div class="item-row flex items-center gap-2">
                    <select name="items[0][category_id]" required
                        class="w-40 shrink-0 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">
                        <option value="">カテゴリ</option>
                        @foreach($selectedTheme->categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="items[0][name]"
                        maxlength="100" required
                        placeholder="項目名（例：基本情報）"
                        class="flex-1 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                    <button type="button" onclick="removeRow(this)" style="display:none;"
                        class="px-2 py-2 text-red-400 hover:text-red-600 text-sm font-bold">✕</button>
                </div>
            </div>

            {{-- ＋ボタン --}}
            <button type="button" onclick="addRow()"
                class="mb-6 flex items-center gap-1 text-sm text-green-600 hover:text-green-800 font-bold">
                <span class="text-lg leading-none">＋</span> 項目を追加
            </button>

            <div class="flex justify-between items-center">
                <a href="{{ route('animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                    追加する
                </button>
            </div>

        </form>

        <script>
            var categoryOptions = `@foreach($selectedTheme->categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach`;

            function addRow() {
                var container = document.getElementById('item-rows');
                var index = container.querySelectorAll('.item-row').length;

                var div = document.createElement('div');
                div.className = 'item-row flex items-center gap-2';
                div.innerHTML =
                    '<select name="items[' + index + '][category_id]" required class="w-40 shrink-0 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white"><option value="">カテゴリ</option>' + categoryOptions + '</select>' +
                    '<input type="text" name="items[' + index + '][name]" maxlength="100" required placeholder="項目名（例：基本情報）" class="flex-1 border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">' +
                    '<button type="button" onclick="removeRow(this)" class="px-2 py-2 text-red-400 hover:text-red-600 text-sm font-bold">✕</button>';

                container.appendChild(div);
                updateRemoveButtons();
            }

            function removeRow(btn) {
                var container = document.getElementById('item-rows');
                btn.closest('.item-row').remove();
                // name の index を振り直す
                container.querySelectorAll('.item-row').forEach(function(row, i) {
                    row.querySelector('select').name = 'items[' + i + '][category_id]';
                    row.querySelector('input').name = 'items[' + i + '][name]';
                });
                updateRemoveButtons();
            }

            function updateRemoveButtons() {
                var rows = document.querySelectorAll('.item-row');
                rows.forEach(function(row) {
                    row.querySelector('button').style.display = rows.length > 1 ? '' : 'none';
                });
            }
        </script>

        @else
        <p class="text-sm text-red-500 mb-6">
            このテーマにはカテゴリがありません。先に
            <a href="{{ route('admin.categories.index') }}" class="underline hover:text-red-700">カテゴリ管理</a>
            でカテゴリを追加してください。
        </p>
        <a href="{{ route('animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
        @endif

        @else

        {{-- テーマ未選択時のメッセージ --}}
        <p class="text-sm text-slate-400 italic text-center py-6">テーマを選択するとカテゴリと項目名を入力できます</p>

        <div class="mt-4">
            <a href="{{ route('animal_welfare.edit') }}" class="text-sm text-slate-400 hover:text-slate-600">← 戻る</a>
        </div>

        @endif

    </div>

</div>
@endsection
