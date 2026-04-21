<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Theme;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // カテゴリ管理ページ（テーマ一覧＋各テーマのカテゴリ一覧を表示）
    // GET /admin/categories
    public function index()
    {
        // テーマとそのカテゴリを一緒に取得する
        $themes = Theme::with(['categories' => function ($query) {
            $query->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        return view('animal_welfare.categories.index', [
            'themes' => $themes,
        ]);
    }

    // カテゴリを新規作成する
    // POST /admin/categories
    public function store(Request $request)
    {
        $request->validate([
            'theme_id' => 'required|exists:themes,id',
            'name'     => 'required|string|max:100',
        ]);

        // 同テーマ内の最大 sort_order を取得し、末尾に追加する
        $maxSortOrder = Category::where('theme_id', $request->theme_id)->max('sort_order') ?? 0;

        Category::create([
            'theme_id'   => $request->theme_id,
            'name'       => $request->name,
            'sort_order' => $maxSortOrder + 1,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを追加しました。');
    }

    // カテゴリの並び順を一括保存する（管理者専用・Ajax）
    // POST /admin/categories/reorder
    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'required|integer|exists:categories,id',
        ]);

        foreach ($request->order as $index => $categoryId) {
            Category::where('id', $categoryId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'カテゴリの並び順を保存しました。']);
    }

    // カテゴリを削除する
    // DELETE /admin/categories/{category}
    public function destroy(Category $category)
    {
        // カテゴリを削除しても、そのカテゴリに属していた項目の
        // category_id は null になる（onDelete('set null') の設定による）
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'カテゴリを削除しました。');
    }
}
