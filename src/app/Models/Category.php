<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['theme_id', 'name', 'sort_order'];

    // このカテゴリが属するテーマ
    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // このカテゴリに属する項目一覧
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
