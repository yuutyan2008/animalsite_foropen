@extends('layouts.app')

@section('title', '動物愛護データ CSVインポート | 管理者')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">動物愛護データ CSVインポート</h1>
        <a href="{{ route('admin.animal_welfare.export_csv') }}"
            class="px-4 py-2 bg-slate-600 text-white text-sm font-bold rounded-xl hover:opacity-80 transition">
            CSVエクスポート
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">

        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-xs text-green-800 space-y-1">
            <p class="font-bold">CSVファイルの形式について</p>
            <ul class="list-disc list-inside space-y-1 text-slate-600">
                <li>「社会問題サイト初期データ」のスプレッドシートをそのままCSV出力して使用できます</li>
                <li>カテゴリ・カテゴリ説明・項目・項目内容を一括登録します</li>
                <li>既に同じ名前のカテゴリ・項目が存在する場合はスキップします</li>
                <li>文字コードはUTF-8またはShift-JISに対応しています</li>
            </ul>
        </div>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 rounded-xl p-4 text-sm font-bold">
            {{ session('success') }}
        </div>
        @endif

        @if(session('import_errors'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-xs text-yellow-800">
            <p class="font-bold mb-2">以下の行はスキップされました：</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.animal_welfare.import_csv.store') }}"
            enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">CSVファイル <span class="text-red-500">*</span></label>
                <input type="file" name="csv_file" accept=".csv,.txt"
                    class="w-full border border-slate-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('csv_file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white text-sm font-bold rounded-xl hover:opacity-90 transition">
                    インポートする
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
