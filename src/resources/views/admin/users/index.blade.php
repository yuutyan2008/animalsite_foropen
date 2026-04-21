@extends('layouts.app')

@section('title', '全てのユーザー')

@section('content')
<div class="max-w-6xl mx-auto">

    <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-6">全てのユーザー</h1>

    {{-- 管理者 --}}
    <h2 class="text-lg font-bold text-slate-600 dark:text-slate-300 mb-4">管理者</h2>
    @include('admin.users._admin_table')

    {{-- 通常ユーザー --}}
    <h2 class="text-lg font-bold text-slate-600 dark:text-slate-300 mt-10 mb-4">通常ユーザー</h2>
    @include('admin.users._table', ['users' => $users, 'title' => '通常ユーザー', 'isBanned' => false])

    {{-- BANユーザー --}}
    @if($bannedUsers->isNotEmpty())
    <h2 class="text-lg font-bold text-slate-600 dark:text-slate-300 mt-10 mb-4">BANしたユーザー</h2>
    @include('admin.users._table', ['users' => $bannedUsers, 'title' => 'BANしたユーザー', 'isBanned' => true])
    @endif

</div>
@endsection
