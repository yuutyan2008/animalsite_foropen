{{-- 【内容編集】既存の内容を編集するフォーム → ContentController::update() --}}
@extends('layouts.app')

@section('title', '内容を編集 | Solvedience')

@section('content')
<div class="max-w-2xl mx-auto">

    <nav class="text-sm text-slate-400 mb-4">
        <a href="{{ route('animal_welfare.edit') }}" class="hover:text-green-600">編集画面</a>
        <span class="mx-2">/</span>
        <span class="text-slate-700">内容を編集</span>
    </nav>

    <h1 class="text-2xl font-bold text-slate-800 mb-2">内容を編集</h1>
    <p class="text-sm text-slate-500 mb-6">項目：<span class="font-bold text-slate-700">{{ $item->name }}</span></p>

    <form method="POST" action="{{ route('items.contents.update', $content) }}"
        class="bg-white border border-slate-200 rounded-2xl p-6 space-y-5">
        @csrf
        @method('PATCH')

        {{-- 題名 --}}
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-1">題名 <span class="text-slate-400 text-xs font-normal">（任意）</span></label>
            <input type="text" name="title"
                value="{{ old('title', $content->title) }}"
                maxlength="200"
                placeholder="例：狂犬病予防（公衆衛生）"
                class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        {{-- 文章＋種別＋URL ペア --}}
        <div x-data="{
            sentences: {{ json_encode(
                old('sentences')
                    ? collect(old('sentences'))->map(fn($s, $i) => [
                        'type'      => $s['type'] ?? 'reference',
                        'value'     => $s['value'] ?? '',
                        'url'       => $s['url'] ?? '',
                        'url_title' => $s['url_title'] ?? '',
                        'loading'   => false,
                      ])->values()
                    : $content->sentences->map(fn($s) => [
                        'type'      => $s->type,
                        'value'     => $s->value,
                        'url'       => $s->url ?? '',
                        'url_title' => $s->url_title ?? '',
                        'loading'   => false,
                      ])->values()
            ) }},
            addSentence() {
                this.sentences.push({ type: 'reference', value: '', url: '', url_title: '', loading: false });
            },
            removeSentence(index) {
                this.sentences.splice(index, 1);
            },
            async fetchTitle(index) {
                const s = this.sentences[index];
                if (!s.url || !s.url.startsWith('http')) return;
                s.loading = true;
                try {
                    const res = await fetch('{{ route('fetch_title') }}?url=' + encodeURIComponent(s.url), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    if (data.title) s.url_title = data.title;
                } catch(e) {}
                s.loading = false;
            }
        }" class="space-y-4">

            <label class="block text-sm font-bold text-slate-700">文章 <span class="text-red-500">*</span></label>

            <template x-for="(sentence, index) in sentences" :key="index">
                <div class="border border-slate-200 rounded-xl p-4 space-y-3 bg-slate-50">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500" x-text="'文章 ' + (index + 1)"></span>
                        <button type="button" @click="removeSentence(index)"
                            x-show="sentences.length > 1"
                            class="text-xs text-red-400 hover:text-red-600">削除</button>
                    </div>

                    {{-- 種別選択（文章ごと） --}}
                    <div class="flex gap-4">
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" :name="'sentences[' + index + '][type]'" value="reference"
                                x-model="sentence.type" class="accent-green-600">
                            <span class="text-xs text-slate-700">出典・参照</span>
                        </label>
                        <label class="flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" :name="'sentences[' + index + '][type]'" value="opinion"
                                x-model="sentence.type" class="accent-green-600">
                            <span class="text-xs text-slate-700">考察・意見</span>
                        </label>
                    </div>

                    {{-- 文章 --}}
                    <textarea :name="'sentences[' + index + '][value]'" rows="3" required maxlength="2000"
                        x-model="sentence.value"
                        placeholder="文章を入力してください"
                        class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white"></textarea>

                    {{-- URL --}}
                    <div>
                        <input type="url" :name="'sentences[' + index + '][url]'"
                            x-model="sentence.url"
                            @blur="fetchTitle(index)"
                            :required="sentence.type === 'reference'"
                            placeholder="出典URL（https://...）"
                            class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 bg-white">

                        <div class="mt-2 min-h-[20px]">
                            <span x-show="sentence.loading" class="text-xs text-slate-400">サイト名を取得中...</span>
                            <div x-show="!sentence.loading && sentence.url_title"
                                class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-1.5">
                                <span class="text-xs text-green-700 font-semibold">出典：</span>
                                <span x-text="sentence.url_title" class="text-xs text-slate-700 flex-1"></span>
                                <button type="button" @click="sentence.url_title = ''" class="text-slate-400 hover:text-red-400 text-xs">✕</button>
                            </div>
                        </div>
                        <input type="hidden" :name="'sentences[' + index + '][url_title]'" x-model="sentence.url_title">
                    </div>
                </div>
            </template>

            <button type="button" @click="addSentence()"
                class="w-full py-2 border-2 border-dashed border-slate-300 rounded-xl text-sm text-slate-400 hover:border-green-400 hover:text-green-600 transition">
                ＋ 文章を追加
            </button>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('animal_welfare.edit') }}"
                class="px-4 py-2 text-sm text-slate-600 border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                キャンセル
            </a>
            <button type="submit"
                class="px-6 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                更新する
            </button>
        </div>
    </form>

</div>
@endsection
