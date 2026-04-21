@extends('layouts.app')

@section('title', '違反を報告する | Solvedience')

@section('content')
<div class="max-w-lg mx-auto">
    <nav class="text-xs text-slate-400 mb-4">
        <a href="{{ route('home') }}" class="hover:text-green-600">ホーム</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">違反を報告する</span>
    </nav>

    <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <h1 class="text-lg font-bold text-slate-800 mb-1">違反を報告する</h1>
        <p class="text-xs text-slate-400 mb-6">内容を確認のうえ、管理者が対応します。</p>

        {{-- 対象の投稿内容 --}}
        <div class="bg-slate-50 rounded-lg px-4 py-3 text-sm text-slate-700 mb-6">
            @if($content->title)
            <p class="font-semibold text-slate-800 mb-1">{{ $content->title }}</p>
            @endif
            <p class="leading-relaxed">
                @foreach($content->sentences as $sentence)
                <span>{{ $sentence->value }}</span>
                @if(!$loop->last)<span> </span>@endif
                @endforeach
            </p>
        </div>

        <form method="POST" action="{{ route('contents.report', $content) }}">
            @csrf

            <div x-data="{ reason: '{{ old('reason') }}' }">
                <fieldset class="mb-4">
                    <legend class="text-sm font-bold text-slate-700 mb-2">報告の理由 <span class="text-red-500">*</span></legend>
                    <div class="space-y-2">
                        @foreach(\App\Models\Report::REASONS as $value => $label)
                        <label class="flex items-center gap-3 text-sm text-slate-600 cursor-pointer">
                            <input type="radio" name="reason" value="{{ $value }}" required
                                class="accent-red-400"
                                x-model="reason"
                                @if(old('reason')===$value) checked @endif>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                    @error('reason')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </fieldset>

                <div class="mb-6" x-show="reason === 'other'" x-cloak>
                    <label for="other_detail" class="text-sm font-bold text-slate-700 mb-1 block">
                        その他の詳細 <span class="text-red-500">*</span><span class="text-slate-400 font-normal text-xs">（50字以内）</span>
                    </label>
                    <textarea id="other_detail" name="other_detail" rows="3" maxlength="50"
                        placeholder="詳しく教えてください"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-red-300">{{ old('other_detail') }}</textarea>
                    @error('other_detail')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg px-6 py-2 transition">
                    報告する
                </button>
                <a href="{{ url()->previous() }}" class="text-sm text-slate-400 hover:text-slate-600 transition">
                    キャンセル
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
