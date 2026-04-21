<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Report;

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'items';

    // statusカラムの値定数（contentsテーブルと同じ3状態で管理する）
    const STATUS_PENDING  = 'pending';  // 承認待ち
    const STATUS_APPROVED = 'approved'; // 承認済み
    const STATUS_REJECTED = 'rejected'; // 却下（削除せずDBに残す）

    protected $fillable = ['theme_id', 'user_id', 'name', 'category_id', 'sort_order', 'status'];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    // この項目が属するカテゴリ
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    public function histories()
    {
        return $this->hasMany(ItemHistory::class)->orderByDesc('history_number');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
