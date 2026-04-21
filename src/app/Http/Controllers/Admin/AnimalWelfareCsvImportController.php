<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Content;
use App\Models\ContentSentence;
use App\Models\Item;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AnimalWelfareCsvImportController extends Controller
{
    public function create()
    {
        return view('admin.animal_welfare.import_csv');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $admin = User::where('role', 1)->first();
        $path  = $request->file('csv_file')->getRealPath();

        // ファイル全体を読み込み、文字コードをUTF-8に変換
        $raw      = file_get_contents($path);
        $encoding = mb_detect_encoding($raw, ['UTF-8', 'SJIS', 'EUC-JP', 'JIS'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $raw = mb_convert_encoding($raw, 'UTF-8', $encoding);
        }
        // BOMを除去
        $raw = ltrim($raw, "\xEF\xBB\xBF");

        // 一時ファイルに書き直してfgetcsvで読む
        $tmp = tmpfile();
        fwrite($tmp, $raw);
        rewind($tmp);

        $allRows = [];
        while (($row = fgetcsv($tmp)) !== false) {
            $allRows[] = $row;
        }
        fclose($tmp);

        // ── ステートマシンで5セクションに振り分け ──────────────────
        $themeRows           = [];
        $categoryRows        = [];
        $itemRows            = [];
        $contentRows         = [];
        $contentSentenceRows = [];

        // セクションマーカーの一覧
        $sectionMarkers = ['categories', 'items', 'contents', 'content_sentences'];

        $state    = 'themes';
        $skipNext = true; // 最初のヘッダー行をスキップ

        foreach ($allRows as $row) {
            $col0 = trim($row[0] ?? '');

            // セクション切り替えマーカー行
            if (in_array($col0, $sectionMarkers, true)) {
                $state    = $col0;
                $skipNext = true; // 次の1行（列ヘッダー）をスキップ
                continue;
            }

            // ヘッダー行・空行をスキップ
            if ($col0 === '' || $skipNext) {
                $skipNext = false;
                continue;
            }

            switch ($state) {
                case 'themes':
                    $themeRows[] = $row;
                    break;
                case 'categories':
                    $categoryRows[] = $row;
                    break;
                case 'items':
                    $itemRows[] = $row;
                    break;
                case 'contents':
                    $contentRows[] = $row;
                    break;
                case 'content_sentences':
                    $contentSentenceRows[] = $row;
                    break;
            }
        }

        // ── DBへの登録 ───────────────────────────────────────────────
        $imported = [
            'themes'            => 0,
            'categories'        => 0,
            'items'             => 0,
            'contents'          => 0,
            'content_sentences' => 0,
        ];
        $errors = [];

        DB::transaction(function () use (
            $admin,
            $themeRows, $categoryRows, $itemRows, $contentRows, $contentSentenceRows,
            &$imported, &$errors
        ) {
            $themeIdMap    = []; // CSV上のtheme_id → DB上のtheme_id
            $categoryIdMap = []; // CSV上のcategory_id → DB上のcategory_id
            $itemIdMap     = []; // CSV上のitem_id → DB上のitem_id
            $contentIdMap  = []; // CSV上のcontent_id → DB上のcontent_id

            // ── テーマ ───────────────────────────────────────────────
            // ヘッダー: id, name, sort_order
            foreach ($themeRows as $row) {
                $csvThemeId = trim($row[0] ?? '');
                $themeName  = trim($row[1] ?? '');
                $sortOrder  = (int) ($row[2] ?? 0);

                if ($csvThemeId === '' || $themeName === '') continue;

                $theme = Theme::firstOrCreate(
                    ['name' => $themeName],
                    ['user_id' => $admin->id, 'sort_order' => $sortOrder]
                );
                $themeIdMap[$csvThemeId] = $theme->id;
                if ($theme->wasRecentlyCreated) {
                    $imported['themes']++;
                }
            }

            // ── カテゴリ ──────────────────────────────────────────────
            // ヘッダー: id, theme_id, name, sort_order
            foreach ($categoryRows as $row) {
                $csvCategoryId = trim($row[0] ?? '');
                $csvThemeId    = trim($row[1] ?? '');
                $categoryName  = trim($row[2] ?? '');
                $sortOrder     = (int) ($row[3] ?? 0);

                if ($csvCategoryId === '' || $csvThemeId === '' || $categoryName === '') continue;

                $dbThemeId = $themeIdMap[$csvThemeId] ?? null;
                if (!$dbThemeId) {
                    $errors[] = "category「{$categoryName}」: テーマID {$csvThemeId} が見つかりません";
                    continue;
                }

                $category = Category::firstOrCreate(
                    ['theme_id' => $dbThemeId, 'name' => $categoryName],
                    ['sort_order' => $sortOrder]
                );
                $categoryIdMap[$csvCategoryId] = $category->id;
                if ($category->wasRecentlyCreated) {
                    $imported['categories']++;
                }
            }

            // ── 項目 ─────────────────────────────────────────────────
            // ヘッダー: id, theme_id, category_id, name, sort_order, status
            foreach ($itemRows as $row) {
                $csvItemId     = trim($row[0] ?? '');
                $csvThemeId    = trim($row[1] ?? '');
                $csvCategoryId = trim($row[2] ?? '');
                $itemName      = trim($row[3] ?? '');
                $sortOrder     = (int) ($row[4] ?? 0);
                $status        = trim($row[5] ?? 'pending');

                if ($csvItemId === '' || $csvThemeId === '' || $itemName === '') continue;

                $dbThemeId = $themeIdMap[$csvThemeId] ?? null;
                if (!$dbThemeId) {
                    $errors[] = "item「{$itemName}」: テーマID {$csvThemeId} が見つかりません";
                    continue;
                }

                $dbCategoryId = ($csvCategoryId !== '') ? ($categoryIdMap[$csvCategoryId] ?? null) : null;
                if ($csvCategoryId !== '' && $dbCategoryId === null) {
                    $errors[] = "item「{$itemName}」: カテゴリID {$csvCategoryId} が見つかりません";
                    continue;
                }

                $item = Item::firstOrCreate(
                    ['theme_id' => $dbThemeId, 'name' => $itemName],
                    [
                        'user_id'     => $admin->id,
                        'category_id' => $dbCategoryId,
                        'sort_order'  => $sortOrder,
                        'status'      => $status,
                    ]
                );
                $itemIdMap[$csvItemId] = $item->id;
                if ($item->wasRecentlyCreated) {
                    $imported['items']++;
                }
            }

            // ── 内容 ─────────────────────────────────────────────────
            // ヘッダー: id, item_id, title, status
            foreach ($contentRows as $row) {
                $csvContentId = trim($row[0] ?? '');
                $csvItemId    = trim($row[1] ?? '');
                $title        = trim($row[2] ?? '') ?: null;
                $status       = trim($row[3] ?? 'pending');

                if ($csvContentId === '' || $csvItemId === '') continue;

                $dbItemId = $itemIdMap[$csvItemId] ?? null;
                if (!$dbItemId) {
                    $errors[] = "content: 項目ID {$csvItemId} が見つかりません";
                    continue;
                }

                $content = Content::create([
                    'item_id' => $dbItemId,
                    'user_id' => $admin->id,
                    'title'   => $title,
                    'status'  => $status,
                ]);
                $contentIdMap[$csvContentId] = $content->id;
                $imported['contents']++;
            }

            // ── 文章 ─────────────────────────────────────────────────
            // ヘッダー: content_id, type, value, url, url_title, sort_order
            foreach ($contentSentenceRows as $row) {
                $csvContentId = trim($row[0] ?? '');
                $type         = trim($row[1] ?? '');
                $value        = trim($row[2] ?? '');
                $url          = trim($row[3] ?? '') ?: null;
                $urlTitle     = trim($row[4] ?? '') ?: null;
                $sortOrder    = (int) ($row[5] ?? 1);

                if ($csvContentId === '' || $type === '' || $value === '') continue;

                $dbContentId = $contentIdMap[$csvContentId] ?? null;
                if (!$dbContentId) {
                    $errors[] = "content_sentence: 内容ID {$csvContentId} が見つかりません";
                    continue;
                }

                ContentSentence::create([
                    'content_id' => $dbContentId,
                    'type'       => $type,
                    'value'      => $value,
                    'url'        => $url,
                    'url_title'  => $urlTitle,
                    'sort_order' => $sortOrder,
                ]);
                $imported['content_sentences']++;
            }
        });

        $message = "登録完了：テーマ {$imported['themes']}件、カテゴリ {$imported['categories']}件、項目 {$imported['items']}件、内容 {$imported['contents']}件、文章 {$imported['content_sentences']}件";

        if (!empty($errors)) {
            return back()->with('success', $message)->with('import_errors', $errors);
        }

        return back()->with('success', $message);
    }
}
