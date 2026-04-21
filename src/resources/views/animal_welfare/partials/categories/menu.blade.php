{{-- カテゴリセルのメニュードロップダウン（管理者専用） --}}
@auth
@if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
<div x-data="{ open: false, showForm: false }" class="relative mt-1">
    <button @click="open = !open" @click.outside="open = false"
        class="text-xs text-slate-400 hover:text-slate-700 hover:underline font-normal">メニュー</button>
    <div x-show="open" x-cloak
        class="absolute left-0 top-5 z-20 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-28">

        <a href="{{ route('admin.categories.index') }}"
            class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">修正</a>

        <button @click="open = false; showForm = true"
            class="block w-full text-left px-4 py-2 text-xs text-green-600 hover:bg-slate-50">
            項目追加
        </button>

        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
            onsubmit="return confirm('「{{ $category->name }}」を削除しますか？')">
            @csrf @method('DELETE')
            <button type="submit"
                class="block w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50">削除</button>
        </form>

    </div>

    {{-- インライン項目追加フォーム --}}
    <div x-show="showForm" x-cloak class="mt-2">
        <form method="POST" action="{{ route('admin.items.quick_store') }}" class="flex gap-1 items-center">
            @csrf
            <input type="hidden" name="theme_id" value="{{ $theme->id }}">
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <input type="text" name="name" required maxlength="100"
                placeholder="項目名"
                class="border border-slate-300 rounded-lg px-2 py-1 text-xs w-28 focus:outline-none focus:ring-1 focus:ring-green-400">
            <button type="submit"
                class="px-2 py-1 bg-green-600 text-white text-xs rounded-lg hover:opacity-80 whitespace-nowrap">
                追加
            </button>
            <button type="button" @click="showForm = false"
                class="px-2 py-1 text-xs text-slate-400 hover:text-slate-600">
                ✕
            </button>
        </form>
    </div>
</div>
@endif
@endauth
