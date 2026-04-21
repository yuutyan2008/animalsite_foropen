<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Theme;

class AnimalWelfareCsvExportController extends Controller
{
    public function export()
    {
        // テーマ・カテゴリ・項目・内容・文章を一括取得
        $themes = Theme::with([
            'categories',
            'items.contents.sentences',
        ])->oldest()->get();

        $rows = [];

        // ────────────────────────────────────
        // セクション1: themes
        // ────────────────────────────────────
        $rows[] = ['id', 'name', 'sort_order'];

        foreach ($themes as $theme) {
            $rows[] = [$theme->id, $theme->name, $theme->sort_order];
        }

        // ────────────────────────────────────
        // セクション2: categories
        // ────────────────────────────────────
        $rows[] = ['categories'];
        $rows[] = ['id', 'theme_id', 'name', 'sort_order'];

        foreach ($themes as $theme) {
            foreach ($theme->categories as $category) {
                $rows[] = [$category->id, $theme->id, $category->name, $category->sort_order];
            }
        }

        // ────────────────────────────────────
        // セクション3: items
        // ────────────────────────────────────
        $rows[] = ['items'];
        $rows[] = ['id', 'theme_id', 'category_id', 'name', 'sort_order', 'status'];

        foreach ($themes as $theme) {
            foreach ($theme->items as $item) {
                $rows[] = [
                    $item->id,
                    $theme->id,
                    $item->category_id ?? '',
                    $item->name,
                    $item->sort_order,
                    $item->status,
                ];
            }
        }

        // ────────────────────────────────────
        // セクション4: contents
        // ────────────────────────────────────
        $rows[] = ['contents'];
        $rows[] = ['id', 'item_id', 'title', 'status'];

        foreach ($themes as $theme) {
            foreach ($theme->items as $item) {
                foreach ($item->contents as $content) {
                    $rows[] = [
                        $content->id,
                        $item->id,
                        $content->title ?? '',
                        $content->status,
                    ];
                }
            }
        }

        // ────────────────────────────────────
        // セクション5: content_sentences
        // ────────────────────────────────────
        $rows[] = ['content_sentences'];
        $rows[] = ['content_id', 'type', 'value', 'url', 'url_title', 'sort_order'];

        foreach ($themes as $theme) {
            foreach ($theme->items as $item) {
                foreach ($item->contents as $content) {
                    foreach ($content->sentences as $sentence) {
                        $rows[] = [
                            $content->id,
                            $sentence->type,
                            $sentence->value,
                            $sentence->url ?? '',
                            $sentence->url_title ?? '',
                            $sentence->sort_order,
                        ];
                    }
                }
            }
        }

        // ────────────────────────────────────
        // CSV文字列を生成（UTF-8 with BOM でLibreOffice/Excelで文字化けしないようにする）
        // ────────────────────────────────────
        $output = fopen('php://temp', 'r+');

        foreach ($rows as $row) {
            $converted = array_map(fn($cell) => (string) $cell, $row);
            fputcsv($output, $converted);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        // BOM（バイト順マーク）をCSVの先頭に付ける
        $csv = "\xEF\xBB\xBF" . $csv;

        $filename = 'animal_welfare_' . now()->format('Ymd_His') . '.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
