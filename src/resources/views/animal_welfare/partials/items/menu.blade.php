{{-- 項目セルのメニュードロップダウン --}}
{{-- 管理者：修正・削除、一般ユーザー：違反報告 --}}
@auth
<div x-data="{ open: false, modal: false }" class="relative mt-1">
    <button @click="open = !open" @click.outside="open = false"
        class="text-xs text-slate-400 hover:text-slate-700 hover:underline font-normal">メニュー</button>
    <div x-show="open" x-cloak
        class="absolute left-0 top-5 z-20 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-28">

        @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        {{-- 管理者：内容追加・修正・削除 --}}
        <a href="{{ route('items.contents.create', $item) }}"
            class="block px-4 py-2 text-xs text-green-600 hover:bg-slate-50">内容追加</a>
        <button @click="open = false; modal = true"
            class="block w-full text-left px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">修正</button>
        <a href="{{ route('admin.items.history', $item->id) }}"
            class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">履歴</a>
        <form method="POST" action="{{ route('admin.items.destroy', $item) }}"
            onsubmit="return confirm('「{{ $item->name }}」を削除しますか？\n関連する内容もすべて削除されます。')">
            @csrf @method('DELETE')
            <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50">削除</button>
        </form>
        @else
        {{-- 一般ユーザー：内容追加・違反報告 --}}
        <a href="{{ route('items.contents.create', $item) }}"
            class="block px-4 py-2 text-xs text-green-600 hover:bg-slate-50">内容追加</a>
        <a href="{{ route('items.report.create', $item) }}"
            class="block px-4 py-2 text-xs text-slate-400 hover:bg-slate-50">
            違反報告
        </a>
        @endif

    </div>

    {{-- 修正モーダル（管理者のみ） --}}
    @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
    <div x-show="modal" x-cloak @keydown.escape.window="modal = false"
        x-effect="if (modal) $nextTick(() => $refs.itemNameInput.focus())"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div @click.away="modal = false"
            class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="font-bold text-slate-800 mb-4">項目を修正</h3>
            <form method="POST" action="{{ route('admin.items.update', $item) }}">
                @csrf @method('PATCH')
                <textarea name="name" required maxlength="100" rows="3"
                    x-ref="itemNameInput"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 mb-4">{{ $item->name }}</textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modal = false"
                        class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50">
                        キャンセル
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-xl hover:opacity-90">
                        保存
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@endauth
