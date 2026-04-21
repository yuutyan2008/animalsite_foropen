<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReportKeptMail;
use App\Mail\ReportDeletedMail;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    // 違反報告一覧：未対応・対応済みを別テーブルで表示
    public function index()
    {
        $unresolvedReports = Report::with([
                'content' => function ($query) {
                    $query->withTrashed()->with(['sentences', 'item']);
                },
                'item',
                'theme',
                'user',
            ])
            ->where('is_resolved', false)
            ->orderByDesc('created_at')
            ->get();

        $resolvedReports = Report::with([
                'content' => function ($query) {
                    $query->withTrashed()->with(['sentences', 'item']);
                },
                'item',
                'theme',
                'user',
            ])
            ->where('is_resolved', true)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.animal_welfare.reports', [
            'unresolvedReports' => $unresolvedReports,
            'resolvedReports'   => $resolvedReports,
        ]);
    }

    // 誤操作時の対応済み取り消し：未対応に戻す（メール通知はしない）
    public function unresolve(Report $report)
    {
        $report->update(['is_resolved' => false]);

        return back()->with('success', '未対応に戻しました。');
    }

    // 掲載継続：報告ユーザーに理由をメール通知し対応済みにする
    public function keep(Request $request, Report $report)
    {
        $request->validate([
            'admin_comment' => 'nullable|string|max:500',
        ]);

        $report->update(['is_resolved' => true]);

        // 報告ユーザーにメール通知（退会済みの場合はスキップ）
        if ($report->user) {
            Mail::to($report->user->email)
                ->send(new ReportKeptMail($report, $request->admin_comment));
        }

        return redirect()->route('admin.social_issues.reports.index')
            ->with('success', '掲載継続として対応しました。報告ユーザーにメールを送信しました。');
    }

    // 削除：投稿ユーザー・報告ユーザー両方にメール通知してソフトデリート
    public function deleteContent(Request $request, Report $report)
    {
        $request->validate([
            'delete_reason' => ['required', Rule::in(array_keys(Report::REASONS))],
            'admin_comment' => 'nullable|string|max:500',
        ]);

        $content = $report->content;

        // --- Step1: DB操作をトランザクションでまとめる ---
        // ここで例外が発生すると自動でロールバックされ、DB変更はすべてなかったことになる
        // メール送信はトランザクションの外で行うため、DB操作が確実に成功してから送信できる
        DB::transaction(function () use ($report, $content) {
            $report->update(['is_resolved' => true]);

            if ($content) {
                $content->delete();
            }
        });

        // --- Step2: DB操作が成功した後にメール送信 ---
        // メールは取り消せないので、削除が確定してから送る
        // トランザクション外なのでメール失敗してもDBはロールバックされないが、
        // 「削除されたのにメールが届かない」は「削除されていないのにメールが届く」より影響が小さい
        if ($content) {
            // 投稿ユーザーにメール通知（退会済みの場合はスキップ）
            if ($content->user) {
                Mail::to($content->user->email)
                    ->send(new ReportDeletedMail(
                        $report,
                        $request->delete_reason,
                        $request->admin_comment,
                        'poster'
                    ));
            }

            // 報告ユーザーにメール通知（退会済みの場合はスキップ）
            if ($report->user) {
                Mail::to($report->user->email)
                    ->send(new ReportDeletedMail(
                        $report,
                        $request->delete_reason,
                        $request->admin_comment,
                        'reporter'
                    ));
            }
        }

        return redirect()->route('admin.social_issues.reports.index')
            ->with('success', '内容を削除しました。投稿ユーザーと報告ユーザーにメールを送信しました。');
    }
}
