<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentHistory extends Model
{
    public $timestamps = false;

    protected $table = 'content_histories';

    // action: 'created'（新規追加）| 'updated'（編集）| 'deleted'（削除）
    protected $fillable = ['content_id', 'user_id', 'history_number', 'action', 'title', 'sentences'];

    protected $casts = [
        'sentences'  => 'array',
        'created_at' => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
