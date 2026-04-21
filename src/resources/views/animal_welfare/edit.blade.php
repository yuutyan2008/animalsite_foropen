@extends('layouts.app')

@section('title', '愛玩動物の問題 | Solvedience')
@section('description', '愛玩動物に関する動物愛護をカテゴリ別に整理しています。')

@section('content')
<div class="flex gap-8 max-w-6xl mx-auto">

    {{-- 左サイドバー：全テーマ・項目一覧（ThemesSidebarComposer が $sidebarThemes を自動注入） --}}
    @include('layouts.partials.themes_sidebar')

    {{-- メインコンテンツ --}}
    <div class="flex-1 min-w-0">

    {{-- 内容作成・修正ボタン（管理者のみ） --}}
    @auth
    @if(auth()->user()->role === \App\Models\User::ROLE_ADMIN)
    <div class="mb-6 flex gap-3">
        <a href="{{ route('admin.animal_welfare.create.theme') }}"
            class="px-5 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition inline-block">
            テーマを作成
        </a>
        <a href="{{ route('admin.animal_welfare.reorder') }}"
            class="px-5 py-2 bg-blue-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition inline-block">
            並び替え
        </a>
        <a href="{{ route('admin.categories.index') }}"
            class="px-5 py-2 bg-purple-500 text-white text-sm font-bold rounded-xl hover:opacity-90 transition inline-block">
            カテゴリ管理
        </a>
    </div>
    @endif
    @endauth

    @if($themes->isEmpty())
    <p class="text-center text-slate-400 py-20">テーマがありません。</p>
    @else
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm min-w-[800px]">
            <thead>
                <tr class="bg-slate-100 text-slate-600 text-left">
                    <th class="border border-slate-200 px-4 py-2 font-bold w-1/6">テーマ</th>
                    <th class="border border-slate-200 px-4 py-2 font-bold w-1/6">カテゴリ</th>
                    <th class="border border-slate-200 px-4 py-2 font-bold w-1/5">項目</th>
                    <th class="border border-slate-200 px-4 py-2 font-bold">内容</th>
                </tr>
            </thead>
            <tbody>
                @foreach($themes as $theme)
                @php
                $itemsByCategoryId = $themeTableData[$theme->id]['itemsByCategoryId'];
                $themeRowspan = $themeTableData[$theme->id]['themeRowspan'];
                $themeFirst = true;
                @endphp

                {{-- =============================================
                     カテゴリを基準にループする
                     項目がないカテゴリも1行表示する
                ============================================= --}}
                @foreach($theme->categories as $category)
                @php
                $categoryItems = $itemsByCategoryId[$category->id] ?? [];
                $categoryRowspan = empty($categoryItems)
                ? 1
                : array_sum(array_map(fn($i) => max($i->contents->count(), 1), $categoryItems));
                $categoryFirst = true;
                @endphp

                @if(empty($categoryItems))
                {{-- このカテゴリに項目がない場合 → 1行だけ表示 --}}
                <tr class="hover:bg-slate-50">
                    @if($themeFirst)
                    <td id="theme-{{ $theme->id }}" class="border border-slate-200 px-4 py-2 font-bold text-slate-800 align-top bg-green-50"
                        rowspan="{{ $themeRowspan }}">
                        {{ $theme->name }}
                        @include('animal_welfare.partials.theme.menu', ['theme' => $theme])
                    </td>
                    @php $themeFirst = false; @endphp
                    @endif

                    <td class="border border-slate-200 px-4 py-2 text-slate-600 font-semibold align-top bg-amber-50">
                        {{ $category->name }}
                        @include('animal_welfare.partials.categories.menu', ['category' => $category, 'theme' => $theme])
                    </td>
                    <td class="border border-slate-200 px-4 py-2 text-slate-300 italic" colspan="2">項目未登録</td>
                </tr>

                @else
                {{-- このカテゴリに項目がある場合 --}}
                @foreach($categoryItems as $item)
                @php
                $itemRowspan = max($item->contents->count(), 1);
                $itemFirst = true;
                @endphp

                @if($item->contents->isNotEmpty())
                @foreach($item->contents as $content)
                <tr class="hover:bg-slate-50">
                    @if($themeFirst)
                    <td id="theme-{{ $theme->id }}" class="border border-slate-200 px-4 py-2 font-bold text-slate-800 align-top bg-green-50"
                        rowspan="{{ $themeRowspan }}">
                        {{ $theme->name }}
                        @include('animal_welfare.partials.theme.menu', ['theme' => $theme])
                    </td>
                    @php $themeFirst = false; @endphp
                    @endif

                    @if($categoryFirst)
                    <td class="border border-slate-200 px-4 py-2 text-slate-600 font-semibold align-top bg-amber-50"
                        rowspan="{{ $categoryRowspan }}">
                        {{ $category->name }}
                        @include('animal_welfare.partials.categories.menu', ['category' => $category, 'theme' => $theme])
                    </td>
                    @php $categoryFirst = false; @endphp
                    @endif

                    @if($itemFirst)
                    <td id="item-{{ $item->id }}" class="border border-slate-200 px-4 py-3 font-semibold text-slate-700 align-top"
                        rowspan="{{ $itemRowspan }}">
                        {{ $item->name }}
                        @include('animal_welfare.partials.items.menu', ['item' => $item])
                    </td>
                    @php $itemFirst = false; @endphp
                    @endif

                    @include('animal_welfare.partials.content.cell', ['content' => $content])
                </tr>
                @endforeach

                @else
                {{-- 項目に内容がない場合 --}}
                <tr class="hover:bg-slate-50">
                    @if($themeFirst)
                    <td id="theme-{{ $theme->id }}" class="border border-slate-200 px-4 py-2 font-bold text-slate-800 align-top bg-green-50"
                        rowspan="{{ $themeRowspan }}">
                        {{ $theme->name }}
                    </td>
                    @php $themeFirst = false; @endphp
                    @endif

                    @if($categoryFirst)
                    <td class="border border-slate-200 px-4 py-2 text-slate-600 font-semibold align-top bg-amber-50"
                        rowspan="{{ $categoryRowspan }}">
                        {{ $category->name }}
                        @include('animal_welfare.partials.categories.menu', ['category' => $category, 'theme' => $theme])
                    </td>
                    @php $categoryFirst = false; @endphp
                    @endif

                    <td id="item-{{ $item->id }}" class="border border-slate-200 px-4 py-3 font-semibold text-slate-700">
                        {{ $item->name }}
                        @include('animal_welfare.partials.items.menu', ['item' => $item])
                    </td>
                    <td class="border border-slate-200 px-4 py-2 text-slate-400 italic">
                        内容未登録
                        @auth
                        <a href="{{ route('items.contents.create', $item) }}"
                            class="block text-xs text-green-600 hover:underline mt-1 not-italic">
                            + 内容を追加
                        </a>
                        @endauth
                    </td>
                </tr>
                @endif

                @endforeach
                @endif

                @endforeach

                {{-- カテゴリも項目もない場合 --}}
                @if($theme->categories->isEmpty() && $theme->items->isEmpty())
                <tr>
                    <td class="border border-slate-200 px-4 py-2 font-bold text-slate-800 bg-green-50">
                        {{ $theme->name }}
                    </td>
                    <td class="border border-slate-200 px-4 py-2 text-slate-400 italic" colspan="3">カテゴリ・項目未登録</td>
                </tr>
                @endif

                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    </div>{{-- flex-1 --}}
</div>{{-- flex --}}
@endsection
