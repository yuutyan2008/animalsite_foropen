<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = ['user_id', 'theme_id', 'item_id', 'content_id', 'reason', 'other_detail', 'is_resolved'];

    // 投稿ガイドの「公開・非公開の判断基準」をそのまま選択肢として使用する
    // ユーザーの違反報告フォームと管理者の削除理由選択の両方で使う
    const REASONS = [
        'no_reference'    => '他のサイトを参考にしていて、出典（URL）が添付されていない',
        'personal_info'   => '個人情報を含む',
        'false_info'      => '虚偽・誇張・根拠のない情報が含まれている',
        'specific_attack' => '特定の団体・業種を名指しで批判するもの（新聞・公式資料など客観的な情報源に基づく内容を除く）',
        'against_ethics'  => '道徳や動物愛護の精神に反すると思われるもの',
        'off_topic'       => '当サービスの趣旨と無関係と思われるもの、その他ユーザーの皆様が不快に感じると思われる内容',
        'other'           => 'その他',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
}
