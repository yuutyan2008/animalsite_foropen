<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 項目新規作成ページ（管理者専用）
    // GET /admin/animal-welfare/create/item
    // theme_id が GET パラメータで渡された場合、そのテーマのカテゴリ一覧を表示する
    public function createPage()
    {
        $themes = Theme::orderBy('sort_order')->get();

        $selectedTheme = null;
        if (request('theme_id')) {
            // theme_id が渡された場合、そのテーマとそのカテゴリ・既存項目を取得する
            $selectedTheme = Theme::with([
                'categories',
                'items' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])->find(request('theme_id'));
        }

        return view('animal_welfare.items.create', [
            'themes'        => $themes,
            'selectedTheme' => $selectedTheme,
        ]);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'theme_id'           => 'required|exists:themes,id',
            'items'              => 'required|array|min:1',
            'items.*.name'       => 'required|string|max:100',
            'items.*.category_id'=> 'required|exists:categories,id',
        ]);

        session()->put('item_confirm', $request->only('theme_id', 'items'));

        return redirect()->route('animal_welfare.items.confirm.show');
    }

    public function confirmShow()
    {
        $data = session('item_confirm');

        if (!$data) {
            return redirect()->route('animal_welfare.items.create');
        }

        $theme = Theme::with('categories')->findOrFail($data['theme_id']);

        return view('animal_welfare.items.confirm', [
            'theme' => $theme,
            'items' => $data['items'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'theme_id'                           => 'required|exists:themes,id',
            'items'                              => 'required|array|min:1',
            'items.*.name'                       => 'required|string|max:100',
            'items.*.category_id'                => 'required|exists:categories,id',
            'items.*.contents'                   => 'nullable|array',
            'items.*.contents.*.value'           => 'required|string|max:2000',
            'items.*.contents.*.reference'       => 'nullable|url|max:500',
            'items.*.contents.*.reference_title' => 'nullable|string|max:200',
        ]);

        $theme = Theme::findOrFail($request->theme_id);

        // 管理者投稿は即承認、一般ユーザー投稿は承認待ちとする
        $status = auth()->user()->role === User::ROLE_ADMIN
            ? Item::STATUS_APPROVED
            : Item::STATUS_PENDING;

        $firstItem = null;
        foreach ($request->items as $itemData) {
            // そのテーマ内の最大 sort_order を取得し、その+1で追加する
            // これにより新規項目は常にそのテーマの一番下に追加される
            $maxSortOrder = $theme->items()->max('sort_order') ?? 0;

            $item = $theme->items()->create([
                'user_id'     => auth()->id(),
                'name'        => $itemData['name'],
                'category_id' => $itemData['category_id'],
                'sort_order'  => $maxSortOrder + 1,
                'status'      => $status,
            ]);
            $this->saveItemHistory($item, 'created');
            if ($firstItem === null) {
                $firstItem = $item;
            }
            foreach ($itemData['contents'] ?? [] as $c) {
                if (!empty($c['value'])) {
                    $item->contents()->create([
                        'user_id'         => auth()->id(),
                        'value'           => $c['value'],
                        'reference'       => $c['reference'] ?? null,
                        'reference_title' => $c['reference_title'] ?? null,
                    ]);
                }
            }
        }

        return back()->with('success', '項目を追加しました。');
    }

    // カテゴリセルからの項目クイック追加（管理者専用）
    // POST /admin/items/quick-store から呼ばれる
    public function quickStore(Request $request)
    {
        $request->validate([
            'theme_id'    => 'required|exists:themes,id',
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:100',
        ]);

        $theme = Theme::findOrFail($request->theme_id);
        $maxSortOrder = $theme->items()->max('sort_order') ?? 0;

        $item = $theme->items()->create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'sort_order'  => $maxSortOrder + 1,
        ]);
        $this->saveItemHistory($item, 'created');

        return back()->with('success', '項目を追加しました。');
    }

    // 項目の並び順を一括保存する（管理者専用・Ajax）
    // リクエスト例: { "order": [5, 3, 4] }  ← 項目IDを並び順に配列で受け取る
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'required|integer|exists:items,id',
        ]);

        // 配列のインデックス番号をそのまま sort_order として保存する
        foreach ($request->order as $index => $itemId) {
            Item::where('id', $itemId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => '項目の並び順を保存しました。']);
    }

    public function update(Request $request, Item $item)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $this->saveItemHistory($item, 'updated');
        $item->update(['name' => $request->name]);
        return back()->with('success', '項目を更新しました。');
    }

    // テーマ内の全項目の名前とカテゴリを一括保存する（管理者専用）
    // PATCH /admin/items/update-all から呼ばれる
    // リクエスト例:
    //   items_name[1] = '基本情報'
    //   items_name[2] = 'これまでの活動'
    //   items_category[1] = '3'   ← カテゴリID（未選択の場合は空文字）
    //   items_category[2] = ''
    public function updateAll(Request $request)
    {
        $request->validate([
            'items_name'              => 'required|array',
            'items_name.*'            => 'required|string|max:100',
            'items_category'          => 'nullable|array',
            'items_category.*'        => 'nullable|exists:categories,id',
        ]);

        // items_name の配列をループして、各項目の名前とカテゴリを更新する
        foreach ($request->items_name as $itemId => $itemName) {
            // items_category が送られてきていない場合や、
            // その項目のカテゴリが空文字の場合は null として扱う
            $categoryId = null;
            if (!empty($request->items_category[$itemId])) {
                $categoryId = $request->items_category[$itemId];
            }

            $item = Item::find($itemId);
            if ($item) {
                $this->saveItemHistory($item, 'updated');
                $item->update([
                    'name'        => $itemName,
                    'category_id' => $categoryId,
                ]);
            }
        }

        if ($request->redirect_to === 'reports') {
            return redirect()->route('admin.social_issues.reports.index')->with('success', '項目を更新しました。');
        }

        return redirect()->route('admin.animal_welfare.items.edit')->with('success', '項目を更新しました。');
    }


    // 項目を削除する（管理者専用）
    public function destroy(Item $item)
    {
        // 削除前にスナップショットを action=deleted で保存してからソフトデリート
        $this->saveItemHistory($item, 'deleted');
        $item->delete();
        return redirect()->route('animal_welfare.edit')->with('success', '項目を削除しました。');
    }

    public function history(int $id)
    {
        // withTrashed() で削除済みの item も履歴ページを表示できるようにする
        $item = Item::withTrashed()->with(['histories.user', 'histories.category', 'theme'])->findOrFail($id);

        return view('admin.animal_welfare.history.item', [
            'item' => $item,
        ]);
    }

    public function restore(int $id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return back()->with('success', '項目を復元しました。');
    }

    public function rollback(Item $item, ItemHistory $history)
    {
        if ($history->item_id !== $item->id) {
            abort(404);
        }

        $this->saveItemHistory($item, 'updated');

        $item->update([
            'name'        => $history->name,
            'category_id' => $history->category_id,
        ]);

        return redirect()
            ->route('admin.items.history', $item)
            ->with('success', "履歴 #{$history->history_number} にロールバックしました。");
    }

    private function saveItemHistory(Item $item, string $action): void
    {
        $nextNumber = $item->histories()->max('history_number') + 1;

        ItemHistory::create([
            'item_id'        => $item->id,
            'user_id'        => auth()->id(),
            'history_number' => $nextNumber,
            'action'         => $action, // 'created' / 'updated' / 'deleted'
            'name'           => $item->name,
            'category_id'    => $item->category_id,
            'created_at'     => now(),
        ]);
    }
}
