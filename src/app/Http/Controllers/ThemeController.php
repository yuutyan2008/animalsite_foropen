<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ThemeController extends Controller
{
    // ホーム画面
    public function home()
    {
        // 注目テーマ：contents の投稿が最近あったテーマ上位5件
        $recentThemeIds = Content::where('status', Content::STATUS_APPROVED)
            ->latest()
            ->limit(50)
            ->pluck('item_id')
            ->unique();

        $featuredThemes = Theme::with([
            'items.contents' => function ($query) {
                $query->where('status', Content::STATUS_APPROVED)->latest()->limit(3);
            },
            'items.contents.sentences',
        ])
            ->whereHas('items', function ($query) use ($recentThemeIds) {
                $query->whereIn('id', $recentThemeIds);
            })
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('home', [
            'featuredThemes' => $featuredThemes,
        ]);
    }

    // テーマ詳細ページ
    public function show(Theme $theme)
    {
        $theme->load([
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items.contents' => function ($query) {
                $query->where('status', Content::STATUS_APPROVED)->orderBy('created_at');
            },
            'items.contents.sentences',
            'items.contents.referenceNeeded',
        ]);

        return view('animal_welfare.show', [
            'theme' => $theme,
        ]);
    }

    // 編集画面（誰でも閲覧可）
    public function edit()
    {
        // contents は承認済みのみ表示する
        // テーマは sort_order 順、項目も sort_order 順に取得する
        $themes = Theme::with([
            'categories' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items.category',
            'items.contents' => function ($query) {
                $query->where('status', Content::STATUS_APPROVED);
            },
            'items.contents.sentences',
            'items.contents.referenceNeeded',
        ])->orderBy('sort_order')->get();

        // テーマごとのグループ化データと rowspan をコントローラで計算する
        $themeTableData = [];
        foreach ($themes as $theme) {
            $itemsByCategoryId = $theme->itemsByCategoryId();
            $themeTableData[$theme->id] = [
                'itemsByCategoryId' => $itemsByCategoryId,
                'themeRowspan'      => $theme->tableRowspan($itemsByCategoryId),
            ];
        }

        return view('animal_welfare.edit', [
            'themes'         => $themes,
            'themeTableData' => $themeTableData,
        ]);
    }

    // テーマ新規作成ページ（管理者専用）
    public function createThemePage()
    {
        // 既存テーマ一覧を参考表示するために取得する
        $themes = Theme::orderBy('sort_order')->get();

        return view('animal_welfare.theme.create', [
            'themes' => $themes,
        ]);
    }

    // 修正トップ（テーマ・項目・内容の選択画面）
    public function editPage()
    {
        return view('animal_welfare.edit');
    }

    // テーマ修正画面
    public function editThemePage()
    {
        $themes = Theme::orderBy('sort_order')->get();

        return view('animal_welfare.theme.edit', [
            'themes' => $themes,
        ]);
    }

    // 項目修正画面
    public function editItemPage()
    {
        $themes = Theme::with([
            'categories',
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items.category',
        ])->orderBy('sort_order')->get();

        return view('animal_welfare.items.edit', [
            'themes' => $themes,
        ]);
    }

    // 内容修正（内容選択画面）
    public function editContentSelectPage()
    {
        $themes = Theme::with([
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'items.contents',
            'items.contents.sentences',
        ])->orderBy('sort_order')->get();

        return view('animal_welfare.content.edit_select', [
            'themes' => $themes,
        ]);
    }

    // 並び替えページ（管理者専用）
    public function reorderPage()
    {
        $themes = Theme::with([
            'items' => function ($query) {
                $query->orderBy('sort_order');
            },
            'categories' => function ($query) {
                $query->orderBy('sort_order');
            },
        ])->orderBy('sort_order')->get();

        // Alpine.js / JS に渡すためにテーマを [{id, name}, ...] の形に変換する
        $themesData = [];
        foreach ($themes as $theme) {
            $themesData[] = [
                'id'   => $theme->id,
                'name' => $theme->name,
            ];
        }
        $themesData = collect($themesData);

        // JS に渡すために項目を [{id, name, theme_id}, ...] の形に変換する
        $itemsData = [];
        foreach ($themes as $theme) {
            foreach ($theme->items as $item) {
                $itemsData[] = [
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'theme_id' => $theme->id,
                ];
            }
        }
        $itemsData = collect($itemsData);

        // JS に渡すためにカテゴリを [{id, name, theme_id}, ...] の形に変換する
        $categoriesData = [];
        foreach ($themes as $theme) {
            foreach ($theme->categories as $category) {
                $categoriesData[] = [
                    'id'       => $category->id,
                    'name'     => $category->name,

                    'theme_id' => $theme->id,
                ];
            }
        }
        $categoriesData = collect($categoriesData);

        return view('animal_welfare.reorder', [
            'themesData'     => $themesData,
            'itemsData'      => $itemsData,
            'categoriesData' => $categoriesData,
        ]);
    }

    // テーマの並び順を一括保存する（管理者専用・Ajax）
    // リクエスト例: { "order": [3, 1, 2] }  ← テーマIDを並び順に配列で受け取る
    public function reorder(Request $request)
    {
        dd($request);
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'required|integer|exists:themes,id',
        ]);

        // 配列のインデックス番号をそのまま sort_order として保存する
        foreach ($request->order as $index => $themeId) {
            Theme::where('id', $themeId)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['message' => 'テーマの並び順を保存しました。']);
    }

    // テーマ名を更新する（管理者専用）
    // PATCH /admin/themes/{theme} から呼ばれる
    // ルートパラメータ名 {theme} と引数名 $theme を一致させることで
    // Laravel がDBからテーマを自動的に取得してくれる（ルートモデルバインディング）
    public function update(Request $request, Theme $theme)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $theme->update(['name' => $request->name]);
        return redirect()->route('admin.animal_welfare.edit.theme')->with('success', 'テーマを更新しました。');
    }

    // テーマを新規作成する（管理者専用）
    // POST /admin/themes から呼ばれる
    // 内容作成ページ（create.blade.php）のフォームから送信される
    public function store(Request $request)
    {
        $request->validate([
            'name'                               => 'required|string|max:100',
            'items'                              => 'nullable|array',
            'items.*.name'                       => 'required|string|max:100',
            'items.*.contents'                   => 'nullable|array',
            'items.*.contents.*.value'           => 'required|string|max:2000',
            'items.*.contents.*.reference'       => 'nullable|url|max:500',
            'items.*.contents.*.reference_title' => 'nullable|string|max:200',
        ]);

        // 既存テーマの最大 sort_order を取得し、その+1を新規テーマに設定する
        // これにより新規テーマは常に一番下に追加される
        $maxSortOrder = Theme::max('sort_order') ?? 0;

        $theme = Theme::create([
            'user_id'    => auth()->id(),
            'name'       => $request->name,
            'sort_order' => $maxSortOrder + 1,
        ]);

        $defaultCategories = [
            1 => '概念・定義',
            2 => '背景・歴史',
            3 => '現状・データ',
            4 => '課題・対策',
            5 => '各立場の意見',
            6 => '考察・疑問',
        ];
        foreach ($defaultCategories as $sortOrder => $categoryName) {
            $theme->categories()->create([
                'name'       => $categoryName,
                'sort_order' => $sortOrder,
            ]);
        }

        foreach ($request->items ?? [] as $itemData) {
            $item = $theme->items()->create(['name' => $itemData['name']]);
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

        return back()->with('success', 'テーマを追加しました。');
    }

    // テーマを削除する（管理者専用）
    public function destroy(Theme $theme)
    {
        $theme->delete();
        return redirect()->route('animal_welfare.edit')->with('success', 'テーマを削除しました。');
    }
}
