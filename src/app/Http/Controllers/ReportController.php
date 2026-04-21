<?php

namespace App\Http\Controllers;

use App\Mail\ReportedMail;
use App\Mail\ReportReceivedMail;
use App\Models\Item;
use App\Models\Content;
use App\Models\Theme;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function create(Content $content)
    {
        return view('reports.create', ['content' => $content]);
    }

    public function createItem(Item $item)
    {
        return view('reports.create_item', ['item' => $item]);
    }

    public function createTheme(Theme $theme)
    {
        return view('reports.create_theme', ['theme' => $theme]);
    }

    public function store(Request $request, Content $content)
    {
        $validated = $request->validate([
            'reason'       => ['required', Rule::in(array_keys(Report::REASONS))],
            'other_detail' => 'nullable|string|max:50|required_if:reason,other',
        ]);

        $already = Report::where('user_id', auth()->id())
            ->where('content_id', $content->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'すでにこの内容を報告済みです。');
        }

        $report = Report::create([
            'user_id'      => auth()->id(),
            'content_id'   => $content->id,
            'reason'       => $validated['reason'],
            'other_detail' => $validated['other_detail'] ?? null,
        ]);

        // リレーションを事前にロードしておく（メール内で使用するため）
        $report->load(['content.item.theme', 'content.sentences', 'user']);

        // 管理者へ違反報告の通知メールを送信
        Mail::to(config('mail.from.address'))
            ->send(new ReportedMail($report));

        // 報告者へ受付確認メールを送信（退会済みの場合はスキップ）
        if ($report->user) {
            Mail::to($report->user->email)
                ->send(new ReportReceivedMail($report));
        }

        return redirect()->route('animal_welfare.show', $content->item->theme)->with('success', '報告を受け付けました。');
    }

    public function storeItem(Request $request, Item $item)
    {
        $validated = $request->validate([
            'reason'       => ['required', Rule::in(array_keys(Report::REASONS))],
            'other_detail' => 'nullable|string|max:50|required_if:reason,other',
        ]);

        $already = Report::where('user_id', auth()->id())
            ->where('item_id', $item->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'すでにこの項目を報告済みです。');
        }

        $report = Report::create([
            'user_id'      => auth()->id(),
            'item_id'      => $item->id,
            'reason'       => $validated['reason'],
            'other_detail' => $validated['other_detail'] ?? null,
        ]);

        // リレーションを事前にロードしておく（メール内で使用するため）
        $report->load(['item.theme', 'user']);

        // 管理者へ違反報告の通知メールを送信
        Mail::to(config('mail.from.address'))
            ->send(new ReportedMail($report));

        // 報告者へ受付確認メールを送信（退会済みの場合はスキップ）
        if ($report->user) {
            Mail::to($report->user->email)
                ->send(new ReportReceivedMail($report));
        }

        return redirect()->route('animal_welfare.show', $item->theme)->with('success', '報告を受け付けました。');
    }

    public function storeTheme(Request $request, Theme $theme)
    {
        $validated = $request->validate([
            'reason'       => ['required', Rule::in(array_keys(Report::REASONS))],
            'other_detail' => 'nullable|string|max:50|required_if:reason,other',
        ]);

        $already = Report::where('user_id', auth()->id())
            ->where('theme_id', $theme->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'すでにこのテーマを報告済みです。');
        }

        Report::create([
            'user_id'      => auth()->id(),
            'theme_id'     => $theme->id,
            'reason'       => $validated['reason'],
            'other_detail' => $validated['other_detail'] ?? null,
        ]);

        return redirect()->route('animal_welfare.show', $theme)->with('success', '報告を受け付けました。');
    }
}
