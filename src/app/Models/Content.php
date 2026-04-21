<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Report;

class Content extends Model
{
    use SoftDeletes;

    protected $table = 'contents';

    const TYPE_REFERENCE = 'reference';
    const TYPE_OPINION   = 'opinion';

    // statusカラムの値定数
    const STATUS_PENDING  = 'pending';  // 承認待ち
    const STATUS_APPROVED = 'approved'; // 承認済み
    const STATUS_REJECTED = 'rejected'; // 却下（削除せずDBに残す）

    protected $fillable = ['item_id', 'user_id', 'title', 'status'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sentences()
    {
        return $this->hasMany(ContentSentence::class)->orderBy('sort_order');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function histories()
    {
        return $this->hasMany(ContentHistory::class)->orderByDesc('history_number');
    }

    public function referenceNeeded()
    {
        return $this->hasOne(ReferenceNeeded::class);
    }
}
