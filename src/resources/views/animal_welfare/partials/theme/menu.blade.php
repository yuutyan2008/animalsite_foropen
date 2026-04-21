{{-- テーマセルのメニュードロップダウン --}}
{{-- 管理者：修正・削除、一般ユーザー：違反報告 --}}
@auth
<div x-data="{ open: false }" class="relative mt-1">
    <button @click="open = !open" @click.outside="open = false"
        class="text-xs text-slate-400 hover:text-slate-700 hover:underline font-normal">メニュー</button>
    <div x-show="open" x-cloak
        class="absolute left-0 top-5 z-20 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-28">

        @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
        {{-- 管理者：修正・削除 --}}
        <a href="{{ route('admin.animal_welfare.edit.theme') }}"
            class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50">修正</a>
        <form method="POST" action="{{ route('admin.animal_welfare.themes.destroy', $theme) }}"
            onsubmit="return confirm('「{{ $theme->name }}」を削除しますか？\n関連する項目・内容もすべて削除されます。')">
            @csrf @method('DELETE')
            <button type="submit" class="block w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50">削除</button>
        </form>
        @else
        {{-- 一般ユーザー：違反報告 --}}
        <a href="{{ route('animal_welfare.report.create', $theme) }}"
            class="block px-4 py-2 text-xs text-slate-400 hover:bg-slate-50">
            違反報告
        </a>
        @endif

    </div>
</div>

@endauth
