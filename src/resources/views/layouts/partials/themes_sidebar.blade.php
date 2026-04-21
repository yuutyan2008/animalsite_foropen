{{--
    サイドバーパーシャル
    - $sidebarThemes : ThemesSidebarComposer が自動注入する（コントローラー不要）
    - $currentTheme  : オプション。現在のテーマをハイライトしたい場合に渡す（show画面で使用）
--}}
<aside class="hidden lg:block w-56 shrink-0">
    <div class="sticky top-8">
        <p class="text-base font-bold text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2 mb-4 text-center">愛玩動物の社会問題</p>
        <nav class="space-y-4">
            @foreach($sidebarThemes as $t)
            <div>
                <a href="{{ route('animal_welfare.show', $t) }}"
                    class="block text-sm font-bold transition mb-1
                        {{ isset($currentTheme) && $t->id === $currentTheme->id
                            ? 'text-green-600'
                            : 'text-slate-700 hover:text-green-600' }}">
                    {{ $t->name }}
                </a>
                @if($t->items->isNotEmpty())
                <ul class="space-y-0.5 ml-2">
                    @foreach($t->items as $item)
                    <li>
                        <a href="{{ route('animal_welfare.show', $t) }}#item-{{ $item->id }}"
                            class="text-xs text-slate-500 hover:text-green-600 transition line-clamp-2">
                            ・{{ $item->name }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endforeach
        </nav>
    </div>
</aside>
