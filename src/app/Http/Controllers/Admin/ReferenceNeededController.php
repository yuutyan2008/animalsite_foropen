<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReferenceNeededMail;
use App\Models\Content;
use App\Models\ReferenceNeeded;
use Illuminate\Support\Facades\Mail;

class ReferenceNeededController extends Controller
{
    // 要出典リスト一覧
    public function index()
    {
        $list = ReferenceNeeded::with([
            'content.user',
            'content.item.theme',
            'content.sentences',
            'addedBy',
        ])
            ->latest()
            ->get();

        return view('admin.reference_needed.index', compact('list'));
    }

    // 要出典リストに追加
    public function store(Content $content)
    {
        // 既に登録済みの場合はスキップ
        ReferenceNeeded::firstOrCreate(
            ['content_id' => $content->id],
            ['added_by'   => auth()->id()]
        );

        return back()->with('success', '要出典リストに追加しました。');
    }

    // 要出典リストから削除（誤操作時の取り消し）
    public function destroy(ReferenceNeeded $referenceNeeded)
    {
        $referenceNeeded->delete();

        return back()->with('success', '要出典リストから削除しました。');
    }

    // 投稿ユーザーへメール送信
    public function sendMail(ReferenceNeeded $referenceNeeded)
    {
        $content = $referenceNeeded->content()->with(['user', 'item.theme', 'sentences'])->first();

        if ($content?->user) {
            Mail::to($content->user->email)
                ->send(new ReferenceNeededMail($referenceNeeded));
        }

        $referenceNeeded->update(['mail_sent_at' => now()]);

        return back()->with('success', 'ユーザーへメールを送信しました。');
    }

    // 要出典アイコン（丸囲み?）を意見文に表示する
    public function appendQuestionMark(ReferenceNeeded $referenceNeeded)
    {
        $referenceNeeded->update(['question_mark_added_at' => now()]);

        return back()->with('success', '要出典アイコンを表示しました。');
    }
}
