@extends('layouts.app') {{-- 既存のレイアウトを継承してデザインを統一 --}}

@section('content')
<div class="max-w-2xl mx-auto py-20 px-4 text-center">
    <div class="bg-white p-8 rounded-lg shadow-md border border-slate-200">
        <div class="text-red-500 mb-4">
        </div>

        <h2 class="text-2xl font-bold text-slate-800 mb-4">セッションの有効期限が切れました</h2>

        <p class="text-slate-600 mb-8 leading-relaxed">
            長時間操作がなかったか、ブラウザの状態により<br>
            一時的に接続が途切れた可能性があります。<br>
            お手数ですが、一度トップページに戻って操作をやり直してください。
        </p>

        <a href="{{ url('/') }}" class="inline-block bg-blue-600 text-white font-bold py-3 px-8 rounded-md hover:bg-blue-700 transition duration-200">
            トップページへ戻る
        </a>
    </div>
</div>
@endsection
