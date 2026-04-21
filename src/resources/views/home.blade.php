@extends('layouts.app')

@section('title', 'Solvedience | 愛玩動物の社会問題')
@section('description', '愛玩動物をめぐる社会問題の背景・現状データ・法律・対策を出典付きでわかりやすく整理したサイトです。')

@section('content')
<div class="flex gap-8 max-w-6xl mx-auto">

    {{-- 左サイドバー：全テーマ・項目一覧（ThemesSidebarComposer が $sidebarThemes を自動注入） --}}
    @include('layouts.partials.themes_sidebar')

    {{-- メインコンテンツ --}}
    <div class="flex-1 min-w-0">

        {{-- サイトの目的 --}}
        <div class="mb-8 border border-dotted border-slate-300 rounded-lg p-4 text-sm text-slate-600 max-w-2xl">
            <p class="font-bold text-slate-700 mb-2">サイトの目的</p>
            <p>犬や猫などをめぐる社会問題の解決のために、皆様に疑問に思っていることや課題についての考察を投稿いただき、多方面からの理解を深めることを目的としています。</p>
            <p>
                <a href="{{ route('posting_guide') }}#features" target="_blank" rel="noopener noreferrer" class="text-green-600 underline hover:text-green-700">投稿の方法</a>
            </p>
            <p>
                <a href="{{ route('posting_guide') }}#flow" target="_blank" rel="noopener noreferrer" class="text-green-600 underline hover:text-green-700">公開までの流れ</a>
            </p>
            <p>
                <a href="{{ route('posting_guide') }}#caution" target="_blank" rel="noopener noreferrer" class="text-green-600 underline hover:text-green-700">投稿の際のご注意</a>
            </p>
        </div>

        {{-- 注目のテーマ --}}
        @if($featuredThemes->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span class="w-1 h-5 bg-green-500 rounded-full inline-block"></span>
                注目のテーマ
            </h2>
            <div class="space-y-6">
                @foreach($featuredThemes as $theme)
                <div class="border border-slate-200 rounded-2xl p-5 hover:shadow-sm transition">
                    <a href="{{ route('animal_welfare.show', $theme) }}"
                        class="text-base font-bold text-green-700 hover:underline">
                        {{ $theme->name }}
                    </a>

                    @foreach($theme->items as $item)
                    @if($item->contents->isNotEmpty())
                    <div class="mt-3">
                        <p class="text-xs font-bold text-slate-500 mb-1">{{ $item->name }}</p>
                        @foreach($item->contents as $content)
                        @php $fullText = $content->sentences->pluck('value')->join(' '); $isLong = Str::length($fullText) > 100; @endphp
                        <div x-data="{ expanded: false }" class="text-sm text-slate-600 bg-slate-50 rounded-lg px-3 py-2 mb-1">
                            @if($content->title)
                            <span class="font-semibold text-slate-700 block mb-0.5">{{ $content->title }}</span>
                            @endif
                            {{-- sentences（URL下線付き）--}}
                            <span :class="!expanded ? 'line-clamp-2' : ''" class="inline-block w-full">
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
                            </span>
                            @if($isLong)
                            <button x-show="!expanded" @click="expanded = true" class="text-green-600 hover:underline text-xs mt-0.5 block">もっと見る</button>
                            <button x-show="expanded" x-cloak @click="expanded = false" class="text-slate-400 hover:underline text-xs mt-0.5 block">閉じる</button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach

                    <a href="{{ route('animal_welfare.show', $theme) }}"
                        class="mt-3 inline-block text-xs text-green-600 hover:underline">
                        すべて見る →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <p class="text-slate-400 text-sm">まだ投稿された内容がありません。</p>
        @endif

    </div>
</div>
@endsection
