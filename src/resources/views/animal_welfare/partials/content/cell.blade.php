{{-- 内容セル：複数文章をインライン表示・メニュー（修正・削除・違反報告）--}}
<td class="border border-slate-200 px-4 py-3 text-slate-600 leading-relaxed">
    <div class="flex flex-col gap-1">
        {{-- 題名（入力された場合のみ表示） --}}
        @if($content->title)
        <span class="block text-xs font-bold text-slate-700 mb-0.5">{{ $content->title }}</span>
        @endif

        {{-- 文章群：改行なしでインライン表示 --}}
        <div class="inline">
            @foreach($content->sentences as $sentence)
            <span x-data="{ open: false }" class="inline relative">
                @if($sentence->url)
                {{-- 出典あり：下線付きクリックでURL表示 --}}
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
                @else
                {{-- 出典なし（考察・意見）：そのまま表示 --}}
                <span>{{ $sentence->value }}</span>
                {{-- 要出典アイコン：管理者が要出典指定した意見文に表示 --}}
                @if($content->referenceNeeded?->question_mark_added_at && $sentence->type === \App\Models\ContentSentence::TYPE_OPINION)
                <span title="出典が必要な可能性があります"
                    class="inline-flex items-center justify-center w-4 h-4 rounded-full bg-slate-100 text-slate-400 text-[10px] font-bold leading-none ml-0.5 align-middle">?</span>
                @endif
                @endif
                {{-- 文章間のスペース --}}
                @if(!$loop->last)<span> </span>@endif
            </span>
            @endforeach
        </div>

        {{-- メニュー：右下 --}}
        @include('animal_welfare.partials.content.menu', ['content' => $content])
    </div>

</td>
