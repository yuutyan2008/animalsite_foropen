{{-- テーマ詳細ページの項目カード --}}
<div id="item-{{ $item->id }}" class="border border-slate-200 rounded-xl p-4">
    <div class="flex items-start justify-between mb-3">
        <h3 class="font-bold text-slate-700 text-sm">{{ $item->name }}</h3>
        @include('animal_welfare.partials.items.menu', ['item' => $item])
    </div>

    @if($item->contents->isNotEmpty())
    <div class="space-y-3">
        @foreach($item->contents as $content)
        <div class="bg-slate-50 rounded-lg px-4 py-3 text-sm text-slate-700">
            <div class="flex items-start gap-2 mb-1">
                <div class="flex-1">
                    @if($content->title)
                    <p class="font-semibold text-slate-800 mb-0.5">{{ $content->title }}</p>
                    @endif
                    <p class="leading-relaxed">
                        @foreach($content->sentences as $sentence)
                        @if($sentence->url)
                        <span x-data="{ open: false }" class="inline relative">
                            <span @click="open = !open"
                                class="underline decoration-dotted decoration-slate-300 underline-offset-2 cursor-pointer hover:decoration-slate-500 transition">{{ $sentence->value }}</span>
                            <span x-show="open" x-cloak @click.away="open = false"
                                class="absolute left-0 top-5 z-10 bg-white border border-slate-200 rounded-xl px-3 py-2 shadow text-xs text-slate-500 w-64 space-y-1">
                                <a href="{{ $sentence->url }}" target="_blank" rel="noopener noreferrer"
                                    class="block text-blue-500 hover:underline">{{ $sentence->url_title ?: $sentence->url }}</a>
                                @if($sentence->url_title)
                                <span class="block text-slate-400 break-all">{{ $sentence->url }}</span>
                                @endif
                            </span>
                        </span>
                        @else
                        <span>{{ $sentence->value }}</span>
                        @endif
                        @if(!$loop->last)<span> </span>@endif
                        @endforeach
                    </p>
                </div>
                @include('animal_welfare.partials.content.menu', ['content' => $content])
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-sm text-slate-400 italic">内容未登録
        @auth
        <a href="{{ route('items.contents.create', $item) }}"
            class="not-italic text-xs text-green-600 hover:underline ml-2">+ 追加する</a>
        @endauth
    </p>
    @endif
</div>
