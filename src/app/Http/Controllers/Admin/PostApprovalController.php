<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ApprovedMail;
use App\Models\Content;
use App\Models\Item;
use Illuminate\Support\Facades\Mail;

class PostApprovalController extends Controller
{
    // 承認管理画面：内容・項目それぞれの承認待ちと却下済みを別テーブルで表示する
    public function index()
    {
        $pendingItems = Item::with(['user', 'theme', 'category'])
            ->where('status', Item::STATUS_PENDING)
            ->latest()
            ->get();

        $rejectedItems = Item::with(['user', 'theme', 'category'])
            ->where('status', Item::STATUS_REJECTED)
            ->latest()
            ->get();

        $pendingContents = Content::with(['user', 'item.theme', 'sentences'])
            ->where('status', Content::STATUS_PENDING)
            ->latest()
            ->get();

        $rejectedContents = Content::with(['user', 'item.theme', 'sentences'])
            ->where('status', Content::STATUS_REJECTED)
            ->latest()
            ->get();

        return view('admin.pending_posts.index', [
            'pendingContents'  => $pendingContents,
            'rejectedContents' => $rejectedContents,
            'pendingItems'     => $pendingItems,
            'rejectedItems'    => $rejectedItems,
        ]);
    }

    // 承認 → status を approved に更新し投稿者にメール通知
    public function approve(Content $content)
    {
        $content->update(['status' => Content::STATUS_APPROVED]);

        if ($content->user) {
            $content->load('item.theme');
            Mail::to($content->user->email)
                ->send(new ApprovedMail($content));
        }

        return back()->with('success', '承認しました。');
    }

    // 却下 → 削除せず status を rejected に更新するだけ（DBに履歴として残す）
    public function reject(Content $content)
    {
        $content->update(['status' => Content::STATUS_REJECTED]);

        return back()->with('success', '却下しました。');
    }

    // 却下取り消し → 承認待ちに戻す
    public function undoReject(Content $content)
    {
        $content->update(['status' => Content::STATUS_PENDING]);

        return back()->with('success', '承認待ちに戻しました。');
    }

    // ===== 項目（Item）の承認管理 =====

    // 項目を承認する → status を approved に更新し投稿者にメール通知
    public function approveItem(Item $item)
    {
        $item->update(['status' => Item::STATUS_APPROVED]);

        if ($item->user) {
            $item->load('theme');
            Mail::to($item->user->email)
                ->send(new ApprovedMail($item));
        }

        return back()->with('success', '項目を承認しました。');
    }

    // 項目を却下する（削除せずstatusをrejectedに更新するだけ）
    public function rejectItem(Item $item)
    {
        $item->update(['status' => Item::STATUS_REJECTED]);

        return back()->with('success', '項目を却下しました。');
    }

    // 項目の却下を取り消して承認待ちに戻す
    public function undoRejectItem(Item $item)
    {
        $item->update(['status' => Item::STATUS_PENDING]);

        return back()->with('success', '項目を承認待ちに戻しました。');
    }

    // 復元（ソフトデリートされた内容を戻す）→ 違反報告からの削除に使用
    public function restore(int $id)
    {
        $content = Content::onlyTrashed()->findOrFail($id);
        $content->restore();

        return back()->with('success', '内容を復元しました。');
    }
}
