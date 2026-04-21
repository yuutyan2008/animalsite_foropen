<?php

namespace App\Http\Controllers;

use App\Mail\ContentPostedMail;
use App\Models\Item;
use App\Models\Content;
use App\Models\ContentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ContentController extends Controller
{
    public function create(Item $item)
    {
        return view('animal_welfare.content.create', ['item' => $item]);
    }

    public function confirm(Request $request, Item $item)
    {
        $request->validate([
            'title'                  => 'nullable|string|max:200',
            'sentences'              => 'required|array|min:1',
            'sentences.*.type'       => 'required|in:reference,opinion',
            'sentences.*.value'      => 'required|string|max:2000',
            'sentences.*.url'        => 'nullable|url|max:500',
            'sentences.*.url_title'  => 'nullable|string|max:200',
        ]);

        session()->put('content_confirm', $request->only('title', 'sentences'));

        return redirect()->route('items.contents.confirm.show', $item);
    }

    public function confirmShow(Item $item)
    {
        $data = session('content_confirm');

        if (!$data) {
            return redirect()->route('items.contents.create', $item);
        }

        return view('animal_welfare.content.confirm', [
            'item'      => $item,
            'title'     => $data['title'] ?? null,
            'sentences' => $data['sentences'] ?? [],
        ]);
    }

    public function store(Request $request, Item $item)
    {
        $request->validate([
            'title'                  => 'nullable|string|max:200',
            'sentences'              => 'required|array|min:1',
            'sentences.*.type'       => 'required|in:reference,opinion',
            'sentences.*.value'      => 'required|string|max:2000',
            'sentences.*.url'        => 'nullable|url|max:500',
            'sentences.*.url_title'  => 'nullable|string|max:200',
        ]);

        // 管理者投稿は即承認、一般ユーザー投稿は承認待ちとする
        $status = auth()->user()->role === User::ROLE_ADMIN
            ? Content::STATUS_APPROVED
            : Content::STATUS_PENDING;

        $content = $item->contents()->create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'status'  => $status,
        ]);

        foreach ($request->sentences as $index => $sentence) {
            $content->sentences()->create([
                'type'       => $sentence['type'],
                'value'      => $sentence['value'],
                'url'        => $sentence['url'] ?? null,
                'url_title'  => $sentence['url_title'] ?? null,
                'sort_order' => $index + 1,
            ]);
        }

        $this->saveContentHistory($content, 'created');

        // 承認待ちの場合のみ管理者にメール通知する（管理者自身の投稿は即承認のため通知不要）
        if ($status === Content::STATUS_PENDING) {
            $content->load('item');
            Mail::to(config('mail.from.address'))
                ->send(new ContentPostedMail($content));
        }

        return redirect()->route('animal_welfare.edit')->with('success', '内容を追加しました。');
    }

    public function edit(Content $content)
    {
        if (
            auth()->user()->role !== User::ROLE_ADMIN
            && $content->user_id !== auth()->id()
        ) {
            abort(403);
        }

        $content->load('sentences');

        return view('animal_welfare.content.edit', [
            'content' => $content,
            'item'    => $content->item,
        ]);
    }

    public function update(Request $request, Content $content)
    {
        if (
            auth()->user()->role !== User::ROLE_ADMIN
            && $content->user_id !== auth()->id()
        ) {
            abort(403);
        }

        $request->validate([
            'title'                  => 'nullable|string|max:200',
            'sentences'              => 'required|array|min:1',
            'sentences.*.type'       => 'required|in:reference,opinion',
            'sentences.*.value'      => 'required|string|max:2000',
            'sentences.*.url'        => 'nullable|url|max:500',
            'sentences.*.url_title'  => 'nullable|string|max:200',
        ]);

        $this->saveContentHistory($content, 'updated');

        $content->update([
            'title' => $request->title,
        ]);

        $content->sentences()->delete();
        foreach ($request->sentences as $index => $sentence) {
            $content->sentences()->create([
                'type'       => $sentence['type'],
                'value'      => $sentence['value'],
                'url'        => $sentence['url'] ?? null,
                'url_title'  => $sentence['url_title'] ?? null,
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()->route('animal_welfare.edit')->with('success', '内容を更新しました。');
    }

    public function destroy(Content $content)
    {
        if (auth()->user()->role !== User::ROLE_ADMIN) {
            abort(403);
        }

        // 削除前にスナップショットを action=deleted で保存してからソフトデリート
        $this->saveContentHistory($content, 'deleted');
        $content->delete();

        return redirect()->route('animal_welfare.edit')->with('success', '内容を削除しました。');
    }

    public function history(int $id)
    {
        // withTrashed() で削除済みの content も取得できるようにする
        $content = Content::withTrashed()->with(['histories.user', 'item'])->findOrFail($id);

        return view('admin.animal_welfare.history.content', [
            'content' => $content,
        ]);
    }

    public function rollback(Content $content, ContentHistory $history)
    {
        if ($history->content_id !== $content->id) {
            abort(404);
        }

        $this->saveContentHistory($content, 'updated');

        $content->update(['title' => $history->title]);

        $content->sentences()->delete();
        foreach ($history->sentences as $index => $sentence) {
            $content->sentences()->create([
                'type'       => $sentence['type'],
                'value'      => $sentence['value'],
                'url'        => $sentence['url'] ?? null,
                'url_title'  => $sentence['url_title'] ?? null,
                'sort_order' => $index + 1,
            ]);
        }

        return redirect()
            ->route('admin.contents.history', $content)
            ->with('success', "履歴 #{$history->history_number} にロールバックしました。");
    }

    private function saveContentHistory(Content $content, string $action): void
    {
        $content->loadMissing('sentences');

        $nextNumber = $content->histories()->max('history_number') + 1;

        ContentHistory::create([
            'content_id'     => $content->id,
            'user_id'        => auth()->id(),
            'history_number' => $nextNumber,
            'action'         => $action, // 'created' / 'updated' / 'deleted'
            'title'          => $content->title,
            'sentences'      => $content->sentences->map(fn($s) => [
                'type'       => $s->type,
                'value'      => $s->value,
                'url'        => $s->url,
                'url_title'  => $s->url_title,
                'sort_order' => $s->sort_order,
            ])->values()->all(),
            'created_at'     => now(),
        ]);
    }

    /**
     * 指定URLのページタイトルを取得して返す（AJAX用）
     */
    public function fetchTitle(Request $request)
    {
        $request->validate(['url' => 'required|url|max:500']);

        // 【SSRF対策】
        // このメソッドはユーザーが入力したURLにサーバー自身がアクセスし、結果を返す。
        // そのため悪意あるユーザーが 169.254.169.254 のようなクラウドの内部アドレスを入力すると、
        // サーバーが代わりにアクセスしてサーバー自身の認証情報を取得し、攻撃者に返してしまう。
        // （169.254.169.254 はAWS・Renderなど全クラウド共通の内部メタデータアドレスで、
        //   外部からはアクセスできないがサーバー自身からはアクセスできる）
        // バリデーションの url ルールはURL形式の正しさしか見ないためこれらは通過してしまう。
        // そのためIPアドレスに変換したうえで、内部アドレスへのアクセスを禁止する。
        $host = parse_url($request->url, PHP_URL_HOST);
        $ip   = gethostbyname($host);
        // プライベートIP、予約済みIPを拒否
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return response()->json(['title' => null]);
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                ->get($request->url);

            if ($response->failed()) {
                return response()->json(['title' => null]);
            }

            $html = mb_convert_encoding($response->body(), 'UTF-8', 'auto');
            preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches);
            $title = isset($matches[1]) ? trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES, 'UTF-8')) : null;

            return response()->json(['title' => $title]);
        } catch (\Throwable) {
            return response()->json(['title' => null]);
        }
    }
}
