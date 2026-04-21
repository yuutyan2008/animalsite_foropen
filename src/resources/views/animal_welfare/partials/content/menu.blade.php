{{-- 内容メニュー（修正・履歴・削除・違反報告）--}}
@auth
<div class="flex justify-end">
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.outside="open = false"
            class="text-xs text-slate-400 hover:text-slate-700 hover:underline">
            メニュー
        </button>
        <div x-show="open" x-cloak
            class="absolute right-0 top-6 z-20 bg-white border border-slate-200 rounded-xl shadow-lg py-1 w-32">

            {{-- 修正：管理者 or 自分の投稿 --}}
            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN || $content->user_id === auth()->id())
            <a href="{{ route('items.contents.edit', $content) }}"
                class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 transition">
                修正
            </a>
            @endif

            {{-- 履歴：管理者のみ --}}
            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
            <a href="{{ route('admin.contents.history', $content->id) }}"
                class="block px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 transition">
                履歴
            </a>
            @endif

            {{-- 削除：管理者のみ --}}
            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
            <form method="POST" action="{{ route('items.contents.destroy', $content) }}"
                onsubmit="return confirm('この内容を削除しますか？\n\n{{ Str::limit($content->title ?: $content->sentences->first()?->value, 30) }}')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="block w-full text-left px-4 py-2 text-xs text-red-500 hover:bg-red-50 transition">
                    削除
                </button>
            </form>
            @endif

            {{-- 要出典：管理者のみ --}}
            @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
            <form method="POST" action="{{ route('admin.reference_needed.store', $content) }}"
                onsubmit="return confirm('この投稿を要出典リストに追加しますか？')">
                @csrf
                <button type="submit"
                    class="block w-full text-left px-4 py-2 text-xs text-orange-500 hover:bg-orange-50 transition">
                    要出典
                </button>
            </form>
            @endif

            {{-- 報告：一般ユーザー（自分の投稿以外） --}}
            @if(auth()->user()->role !== \App\Models\User::ROLE_ADMIN && $content->user_id !== auth()->id())
            <a href="{{ route('contents.report.create', $content) }}"
                class="block px-4 py-2 text-xs text-slate-400 hover:bg-slate-50 transition">
                違反報告
            </a>
            @endif

        </div>
    </div>
</div>
@endauth
