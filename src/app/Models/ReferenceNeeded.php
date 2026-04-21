<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenceNeeded extends Model
{
    protected $table = 'reference_needed';

    protected $fillable = ['content_id', 'added_by', 'mail_sent_at', 'question_mark_added_at'];

    protected $casts = [
        'mail_sent_at'            => 'datetime',
        'question_mark_added_at'  => 'datetime',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
