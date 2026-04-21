<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Content;

class HistoryController extends Controller
{
    /**
     * 削除済みの項目・内容を一覧表示する。
     *
     * Item::onlyTrashed()    → items テーブルの deleted_at が NULL でないレコードだけ取得
     * Content::onlyTrashed() → contents テーブルの deleted_at が NULL でないレコードだけ取得
     *
     * 復元・履歴確認は各モデルのコントローラ（ItemController / ContentController）が担当する。
     */
    public function deleted()
    {
        $deletedItems = Item::onlyTrashed()
            ->with(['theme', 'category'])
            ->latest('deleted_at')
            ->get();

        $deletedContents = Content::onlyTrashed()
            ->with(['item.theme', 'user'])
            ->latest('deleted_at')
            ->get();

        return view('admin.animal_welfare.deleted_history', [
            'deletedItems'    => $deletedItems,
            'deletedContents' => $deletedContents,
        ]);
    }
}
