@extends('layouts.app')

@section('title', 'お問い合わせ')

@section('content')
<div class="max-w-lg mx-auto mt-12">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm p-8">

        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">お問い合わせ</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
            ご質問・ご要望・不具合のご報告などはこちらからお送りください。
        </p>

        <form method="POST" action="{{ route('contact.confirm') }}">
            @csrf

            {{-- お名前 --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    お名前 <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('name') border-red-400 @enderror"
                    placeholder="山田 太郎">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- メールアドレス --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    メールアドレス <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-400 @enderror"
                    placeholder="example@gmail.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 件名 --}}
            <div class="mb-4">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    件名 <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('subject') border-red-400 @enderror"
                    placeholder="使い方について">
                @error('subject')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- お問い合わせ内容 --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1">
                    お問い合わせ内容 <span class="text-red-500">*</span>
                </label>
                <textarea name="body" rows="6"
                    class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('body') border-red-400 @enderror"
                    placeholder="お問い合わせ内容をご記入ください（2000文字以内）">{{ old('body') }}</textarea>
                @error('body')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full px-4 py-2 bg-primary text-white font-bold rounded-xl hover:opacity-90 transition text-sm">
                確認画面へ
            </button>
        </form>

    </div>
</div>
@endsection
