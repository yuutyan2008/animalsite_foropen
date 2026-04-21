@extends('layouts.app')

@section('content')
{{-- 背景を薄い青に、中身を中央寄せに --}}
<div class="min-h-screen w-full flex flex-col items-center justify-center bg-blue-50 py-12">

    <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl rounded-2xl">

        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900">メールアドレスの変更</h2>
            <p class="text-blue-500 mt-2 font-bold">新しいアドレスを入力してください</p>
        </div>

        {{-- 成功メッセージ --}}
        @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-600 rounded-lg text-sm font-bold">
            {{ session('status') }}
        </div>
        @endif

        <form id="updateForm" method="POST" action="{{ route('email.update') }}">
            @csrf

            <div class="mb-8">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">新しいメールアドレス</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-400 outline-none @error('email') border-red-500 @enderror">

                @error('email')
                <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <button type="button" id="submitBtn" onclick="handleUpdate()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg transition-all focus:outline-none">
                更新する
            </button>
        </form>
    </div>
</div>

<script>
    /**
     * 更新ボタンが押された時の処理
     */
    function handleUpdate() {
        // 1. ボタンの要素を取得する
        const btn = document.getElementById('submitBtn');
        // 2. フォームの要素を取得する
        const form = document.getElementById('updateForm');

        // フォーム全体のバリデーションチェック（未入力などをブラウザ側で防ぐ）
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // 3. ボタンを無効化する（連打防止）
        btn.disabled = true;

        // 4. ボタンの文字を変える（安心感を与える）
        btn.innerText = '処理中...';

        // 5. オーバーレイを表示してフォームを送信する
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.classList.remove('hidden');
        form.submit();
    }
</script>
</div>
@endsection
