@extends('layouts.app')

@section('title', '並び替え | Solvedience')

@section('content')

{{-- Sortable.js を CDN から読み込む --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <span class="text-slate-600">並び替え</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-6">並び替え</h1>

    {{-- ============================================================ --}}
    {{-- テーマの並び替え --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-6">

        <p class="text-sm font-bold text-slate-700 mb-1">テーマの並び替え</p>
        <p class="text-xs text-slate-400 mb-4">ドラッグして並び替えてください</p>

        {{-- Sortable.js が操作するリスト --}}
        <ul id="theme-sort-list" class="space-y-2 mb-4">
            @foreach($themesData as $theme)
            <li data-id="{{ $theme['id'] }}"
                class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-grab active:cursor-grabbing select-none">
                {{-- ドラッグハンドルのアイコン（縦の点線） --}}
                <span class="text-slate-300 text-lg leading-none">&#8942;&#8942;</span>
                <span class="text-sm font-bold text-slate-700">{{ $theme['name'] }}</span>
            </li>
            @endforeach
        </ul>

        {{-- 保存結果メッセージ表示エリア --}}
        <p id="theme-reorder-message" class="text-sm text-center mb-4 hidden"></p>

        <div class="flex justify-end">
            <button onclick="saveThemeOrder()"
                class="px-6 py-2 bg-blue-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                並び順を保存
            </button>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- カテゴリの並び替え --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 mb-6">

        <p class="text-sm font-bold text-slate-700 mb-3">カテゴリの並び替え</p>

        <p class="text-xs text-slate-500 mb-1">対象のテーマを選択してください</p>
        <select id="category-reorder-theme-select"
            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"
            onchange="renderCategoryReorderList(this.value)">
            <option value="">-- テーマを選択 --</option>
            @foreach($themesData as $theme)
            <option value="{{ $theme['id'] }}">{{ $theme['name'] }}</option>
            @endforeach
        </select>

        <p class="text-xs text-slate-400 mb-3" id="category-reorder-hint" style="display:none;">ドラッグして並び替えてください</p>

        <ul id="category-sort-list" class="space-y-2 mb-4"></ul>

        <p id="category-reorder-message" class="text-sm text-center mb-4 hidden"></p>

        <div class="flex justify-end">
            <button onclick="saveCategoryOrder()"
                class="px-6 py-2 bg-blue-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                並び順を保存
            </button>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- 項目の並び替え --}}
    {{-- ============================================================ --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">

        <p class="text-sm font-bold text-slate-700 mb-3">項目の並び替え</p>

        {{-- テーマを選択すると、そのテーマの項目リストが表示される --}}
        <p class="text-xs text-slate-500 mb-1">対象のテーマを選択してください</p>
        <select id="item-reorder-theme-select"
            class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400"
            onchange="renderItemReorderList(this.value)">
            <option value="">-- テーマを選択 --</option>
            @foreach($themesData as $theme)
            <option value="{{ $theme['id'] }}">{{ $theme['name'] }}</option>
            @endforeach
        </select>

        <p class="text-xs text-slate-400 mb-3" id="item-reorder-hint" style="display:none;">ドラッグして並び替えてください</p>

        {{-- テーマ選択後に項目リストがここに描画される --}}
        <ul id="item-sort-list" class="space-y-2 mb-4"></ul>

        {{-- 保存結果メッセージ表示エリア --}}
        <p id="item-reorder-message" class="text-sm text-center mb-4 hidden"></p>

        <div class="flex justify-end">
            <button onclick="saveItemOrder()"
                class="px-6 py-2 bg-blue-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                並び順を保存
            </button>
        </div>

    </div>

</div>

<script>
    // ============================================================
    // テーマの並び替え
    // ============================================================

    // ページ読み込み後にテーマリストへ Sortable を適用する
    document.addEventListener('DOMContentLoaded', function() {
        var themeList = document.getElementById('theme-sort-list');
        if (themeList) {
            Sortable.create(themeList, {
                animation: 150, // ドラッグ中のアニメーション速度（ミリ秒）
                ghostClass: 'opacity-40', // ドラッグ中の元の位置を半透明にする
            });
        }
    });

    // 「並び順を保存」ボタンを押したときにテーマの順番をサーバーへ送信する
    function saveThemeOrder() {
        var list = document.getElementById('theme-sort-list');
        var items = list.querySelectorAll('li');

        // 現在の並び順に従って ID を配列に収集する
        var orderedIds = [];
        for (var i = 0; i < items.length; i++) {
            orderedIds.push(items[i].getAttribute('data-id'));
        }

        // CSRF トークンを meta タグから取得する
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // 保存中メッセージを表示する
        var messageEl = document.getElementById('theme-reorder-message');
        messageEl.textContent = '保存中...';
        messageEl.className = 'text-sm text-center mb-4 text-slate-400';
        messageEl.classList.remove('hidden');

        // fetch を使って Ajax で POST リクエストを送信する
        fetch('/admin/themes/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    order: orderedIds
                }),
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('通信エラー');
                }
                return response.json();
            })
            .then(function(data) {
                // 保存成功
                messageEl.textContent = '✓ 並び順を保存しました';
                messageEl.className = 'text-sm text-center mb-4 text-green-600';
            })
            .catch(function(error) {
                // 保存失敗
                messageEl.textContent = '保存に失敗しました。再度お試しください。';
                messageEl.className = 'text-sm text-center mb-4 text-red-500';
            });
    }

    // ============================================================
    // 項目の並び替え
    // ============================================================

    // ============================================================
    // カテゴリの並び替え
    // ============================================================

    var allCategoriesData = @json($categoriesData);
    var categorySortableInstance = null;

    function renderCategoryReorderList(themeId) {
        var list = document.getElementById('category-sort-list');
        var hint = document.getElementById('category-reorder-hint');

        list.innerHTML = '';

        if (!themeId) {
            hint.style.display = 'none';
            return;
        }

        var filtered = allCategoriesData.filter(function(c) {
            return c.theme_id == themeId;
        });

        if (filtered.length === 0) {
            list.innerHTML = '<li class="text-sm text-slate-400 px-4 py-3">このテーマにはカテゴリがありません</li>';
            hint.style.display = 'none';
            return;
        }

        hint.style.display = 'block';

        filtered.forEach(function(category) {
            var li = document.createElement('li');
            li.setAttribute('data-id', category.id);
            li.className = 'flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-grab active:cursor-grabbing select-none';

            var handle = document.createElement('span');
            handle.className = 'text-slate-300 text-lg leading-none';
            handle.innerHTML = '&#8942;&#8942;';

            var label = document.createElement('span');
            label.className = 'text-sm font-bold text-slate-700';
            label.textContent = category.value ? category.name + '（' + category.value + '）' : category.name;

            li.appendChild(handle);
            li.appendChild(label);
            list.appendChild(li);
        });

        if (categorySortableInstance) {
            categorySortableInstance.destroy();
        }
        categorySortableInstance = Sortable.create(list, {
            animation: 150,
            ghostClass: 'opacity-40',
        });
    }

    function saveCategoryOrder() {
        var list = document.getElementById('category-sort-list');
        var items = list.querySelectorAll('li[data-id]');

        if (items.length === 0) {
            alert('テーマを選択してから保存してください。');
            return;
        }

        var orderedIds = Array.from(items).map(function(li) {
            return li.getAttribute('data-id');
        });
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var messageEl = document.getElementById('category-reorder-message');
        messageEl.textContent = '保存中...';
        messageEl.className = 'text-sm text-center mb-4 text-slate-400';
        messageEl.classList.remove('hidden');

        fetch('/admin/categories/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    order: orderedIds
                }),
            })
            .then(function(response) {
                if (!response.ok) throw new Error('通信エラー');
                return response.json();
            })
            .then(function() {
                messageEl.textContent = '✓ 並び順を保存しました';
                messageEl.className = 'text-sm text-center mb-4 text-green-600';
            })
            .catch(function() {
                messageEl.textContent = '保存に失敗しました。再度お試しください。';
                messageEl.className = 'text-sm text-center mb-4 text-red-500';
            });
    }

    // PHP から渡された全項目データを JS で使えるようにする
    var allItemsData = @json($itemsData);

    // 項目並び替え用の Sortable インスタンスを保持しておく変数
    var itemSortableInstance = null;

    // テーマを選択したときに、そのテーマに属する項目リストを描画する
    function renderItemReorderList(themeId) {
        var list = document.getElementById('item-sort-list');
        var hint = document.getElementById('item-reorder-hint');

        // リストを一度空にする
        list.innerHTML = '';

        if (!themeId) {
            hint.style.display = 'none';
            return;
        }

        // 選択されたテーマに属する項目だけを絞り込む
        var filteredItems = [];
        for (var i = 0; i < allItemsData.length; i++) {
            if (allItemsData[i].theme_id == themeId) {
                filteredItems.push(allItemsData[i]);
            }
        }

        if (filteredItems.length === 0) {
            list.innerHTML = '<li class="text-sm text-slate-400 px-4 py-3">このテーマには項目がありません</li>';
            hint.style.display = 'none';
            return;
        }

        hint.style.display = 'block';

        // 項目ごとに li 要素を生成してリストに追加する
        for (var j = 0; j < filteredItems.length; j++) {
            var item = filteredItems[j];

            var li = document.createElement('li');
            li.setAttribute('data-id', item.id);
            li.className = 'flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 cursor-grab active:cursor-grabbing select-none';

            // ドラッグハンドルアイコン
            var handle = document.createElement('span');
            handle.className = 'text-slate-300 text-lg leading-none';
            handle.innerHTML = '&#8942;&#8942;';

            // 項目名
            var name = document.createElement('span');
            name.className = 'text-sm font-bold text-slate-700';
            name.textContent = item.name;

            li.appendChild(handle);
            li.appendChild(name);
            list.appendChild(li);
        }

        // 既存の Sortable インスタンスがあれば一度破棄してから新しく作る
        if (itemSortableInstance) {
            itemSortableInstance.destroy();
        }
        itemSortableInstance = Sortable.create(list, {
            animation: 150,
            ghostClass: 'opacity-40',
        });
    }

    // 「並び順を保存」ボタンを押したときに項目の順番をサーバーへ送信する
    function saveItemOrder() {
        var list = document.getElementById('item-sort-list');
        var items = list.querySelectorAll('li[data-id]');

        if (items.length === 0) {
            alert('テーマを選択してから保存してください。');
            return;
        }

        // 現在の並び順に従って ID を配列に収集する
        var orderedIds = [];
        for (var i = 0; i < items.length; i++) {
            orderedIds.push(items[i].getAttribute('data-id'));
        }

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var messageEl = document.getElementById('item-reorder-message');
        messageEl.textContent = '保存中...';
        messageEl.className = 'text-sm text-center mb-4 text-slate-400';
        messageEl.classList.remove('hidden');

        fetch('/admin/items/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    order: orderedIds
                }),
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('通信エラー');
                }
                return response.json();
            })
            .then(function(data) {
                messageEl.textContent = '✓ 並び順を保存しました';
                messageEl.className = 'text-sm text-center mb-4 text-green-600';
            })
            .catch(function(error) {
                messageEl.textContent = '保存に失敗しました。再度お試しください。';
                messageEl.className = 'text-sm text-center mb-4 text-red-500';
            });
    }
</script>
@endsection
