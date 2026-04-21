<div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider">
            <tr>
                <th class="px-4 py-3 text-left">名前</th>
                <th class="px-4 py-3 text-left">メールアドレス</th>
                <th class="px-4 py-3 text-center">違反報告を受けた数</th>
                <th class="px-4 py-3 text-center">登録日</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
            @forelse($adminUsers as $user)
            <tr>
                <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-100">
                    {{ $user->name }}
                </td>
                <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                    {{ $user->email }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($user->report_count > 0)
                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                        {{ $user->report_count }}件
                    </span>
                    @else
                    <span class="text-slate-300">-</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">
                    {{ $user->created_at->format('Y/m/d') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-8 text-center text-slate-400">管理者がいません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
