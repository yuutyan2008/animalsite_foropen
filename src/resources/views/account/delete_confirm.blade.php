@extends('layouts.app')

@section('title', '退会確認')

@section('content')
<div class="max-w-lg mx-auto mt-12">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm p-8">

        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">退会の確認</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            退会すると、アカウント情報が削除されます。この操作は取り消せません。
        </p>

        {{-- 注意事項 --}}
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-3 text-sm text-red-700">
            <p class="font-bold mb-1">以下のデータが削除されます：</p>
            <ul class="list-disc list-inside space-y-1">
                <li>アカウント情報（名前・メールアドレス）</li>
            </ul>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-sm text-blue-700">
            <p class="font-bold mb-1">以下のデータは残ります：</p>
            <ul class="list-disc list-inside space-y-1">
                <li>投稿した記事・内容（匿名扱いになります）</li>
            </ul>
            <p class="mt-2 text-blue-500">※退会後も同じGoogleアカウントで再入会できます</p>
        </div>

        {{-- 退会理由フォーム --}}
        <form method="POST" action="{{ route('account.destroy') }}"
            onsubmit="if (!confirm('本当に退会しますか？この操作は取り消せません。')) { event.preventDefault(); return false; }">
            @csrf
            @method('DELETE')

            <div class="mb-6">
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mb-3">
                    今後のサービス改善に活用するため、退会される理由をお選びください<br>
                    <span class="text-slate-400 font-normal">（任意）</span>
                </p>

                <div class="space-y-2 text-sm text-slate-700 dark:text-slate-300">
                    @php
                    $reasons = [
                    'not_useful' => '情報が少なく使えなかった',
                    'unsatisfied' => '内容に満足できなかった',
                    'hard_to_use' => '使い方がわかりにくかった',
                    'no_longer_needed' => 'もう必要がなくなった',
                    'other' => 'その他',
                    ];
                    @endphp

                    @foreach($reasons as $value => $label)
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="reason" value="{{ $value }}"
                            class="text-red-500 focus:ring-red-400"
                            {{ old('reason') === $value ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                    @endforeach
                </div>

                {{-- Alpine.jsで「その他」のみ自由記述欄を表示 --}}
                <div x-data="{ showOther: {{ old('reason') === 'other' ? 'true' : 'false' }} }"
                    x-init="
                        document.querySelectorAll('input[name=reason]').forEach(el => {
                            el.addEventListener('change', e => { showOther = (e.target.value === 'other') });
                        });
                    ">
                    <textarea
                        x-show="showOther"
                        x-cloak
                        name="reason_other"
                        rows="3"
                        placeholder="具体的に教えていただけると助かります"
                        class="mt-3 w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300">{{ old('reason_other') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3">
                {{-- キャンセル --}}
                <a href="{{ route('animal_welfare.edit') }}"
                    class="flex-1 text-center px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition text-sm">
                    キャンセル
                </a>

                {{-- 退会実行 --}}
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition text-sm">
                    退会する
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
