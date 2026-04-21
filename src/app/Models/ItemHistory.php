<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemHistory extends Model
{
    public $timestamps = false;

    // action: 'created'（新規追加）| 'updated'（編集）| 'deleted'（削除）
    protected $fillable = ['item_id', 'user_id', 'history_number', 'action', 'name', 'category_id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
