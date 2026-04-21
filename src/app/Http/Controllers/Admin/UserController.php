<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannedGoogleId;
use App\Models\User;

class UserController extends Controller
{
    // ユーザー一覧
    public function index()
    {
        $withReportCount = [
            'contents as content_report_count' => function ($query) {
                $query->whereHas('reports');
            },
            'items as item_report_count' => function ($query) {
                $query->whereHas('reports');
            },
        ];

        $sumReportCount = function ($user) {
            $user->report_count = $user->content_report_count + $user->item_report_count;
        };

        $isDemoAdmin = auth()->user()->email === 'demo-admin@example.com';
        $demoEmails = ['demo-admin@example.com', 'demo-user@example.com'];

        $users = User::withCount($withReportCount)
            ->where('role', '!=', User::ROLE_ADMIN)
            ->where('is_banned', false)
            ->when($isDemoAdmin, fn($q) => $q->whereIn('email', $demoEmails))
            ->orderByDesc('created_at')
            ->get()
            ->each($sumReportCount);

        $bannedUsers = User::withCount($withReportCount)
            ->where('role', '!=', User::ROLE_ADMIN)
            ->where('is_banned', true)
            ->when($isDemoAdmin, fn($q) => $q->whereIn('email', $demoEmails))
            ->orderByDesc('created_at')
            ->get()
            ->each($sumReportCount);

        $adminUsers = User::withCount($withReportCount)
            ->where('role', User::ROLE_ADMIN)
            ->when($isDemoAdmin, fn($q) => $q->whereIn('email', $demoEmails))
            ->orderByDesc('created_at')
            ->get()
            ->each($sumReportCount);

        return view('admin.users.index', compact('users', 'bannedUsers', 'adminUsers'));
    }

    // BANする
    public function ban(User $user)
    {
        $user->update(['is_banned' => true]);

        // 再入会を防ぐためgoogle_idをBANリストに保存
        if ($user->google_id) {
            BannedGoogleId::firstOrCreate(['google_id' => $user->google_id]);
        }

        return back()->with('success', "{$user->name} さんのアカウントを停止しました。");
    }

    // BAN解除する
    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', "{$user->name} さんのアカウント停止を解除しました。");
    }
}
