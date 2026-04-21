<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
            <tr>
                <th class="px-4 py-3 text-left">名前</th>
                <th class="px-4 py-3 text-left">メールアドレス</th>
                <th class="px-4 py-3 text-center">違反報告回数</th>
                <th class="px-4 py-3 text-center">登録日</th>
                <th class="px-4 py-3 text-center">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($users as $user)
            <tr class="{{ $isBanned ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">
                    {{ $user->name }}
                </td>
                <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                    {{ $user->email }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($user->report_count > 0)
                    <span class="text-slate-700 dark:text-slate-300 text-xs font-bold">
                        {{ $user->report_count }}件
                    </span>
                    @else
                    <span class="text-slate-300">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">
                    {{ $user->created_at->format('Y/m/d') }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if(auth()->user()->email !== 'demo-admin@example.com')
                        @if($isBanned)
                        <form method="POST" action="{{ route('admin.users.unban', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition">
                                BAN解除
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.users.ban', $user) }}"
                            onsubmit="if (!confirm('{{ $user->name }} さんのアカウントを停止しますか？')) { event.preventDefault(); return false; }">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-3 py-1 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition">
                                BAN
                            </button>
                        </form>
                        @endif
                    @else
                        <span class="text-slate-300 text-xs">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-slate-400">ユーザーがいません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
