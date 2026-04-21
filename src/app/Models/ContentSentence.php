<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSentence extends Model
{
    protected $table = 'content_sentences';

    const TYPE_REFERENCE = 'reference';
    const TYPE_OPINION   = 'opinion';

    protected $fillable = ['content_id', 'type', 'value', 'url', 'url_title', 'sort_order'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
