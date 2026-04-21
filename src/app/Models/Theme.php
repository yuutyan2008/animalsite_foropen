<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // このテーマが持つカテゴリ一覧
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // 項目をカテゴリIDをキーにしてグループ化して返す（category_id が null の項目は除外）
    public function itemsByCategoryId(): array
    {
        $grouped = [];
        foreach ($this->items as $item) {
            if ($item->category_id !== null) {
                $grouped[(int) $item->category_id][] = $item;
            }
        }
        return $grouped;
    }

    // テーブルのテーマ列の rowspan を計算して返す
    public function tableRowspan(array $itemsByCategoryId): int
    {
        $rowspan = 0;

        foreach ($this->categories as $cat) {
            $catItems = $itemsByCategoryId[$cat->id] ?? [];
            if (empty($catItems)) {
                $rowspan += 1;
            } else {
                foreach ($catItems as $item) {
                    $rowspan += max($item->contents->count(), 1);
                }
            }
        }

        return max($rowspan, 1);
    }
}
