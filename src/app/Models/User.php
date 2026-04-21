<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword; // パスワードリセット通知のカスタムクラスをインポート
use App\Models\Content;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory,   // テスト用ダミーデータの生成を可能にする
        Notifiable,  // メール・通知の送信を可能にする
        SoftDeletes; // 論理削除を有効にする

    // roleカラムの数値を定数に定義して各ユーザ専用の処理に使用
    const ROLE_ADMIN = 1;
    const ROLE_USER = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'google_id',
        'name',
        'email',
        'password',
        'role',
        'is_banned',
    ];

    /**
     * システム管理者かどうかを判定する
     */
    public function isSystemAdmin(): bool
    {
        return $this->role === '1';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'  => 'hashed',
            'role'      => 'integer',
            'is_banned' => 'boolean',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    // 投稿した内容
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    // 投稿した項目
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
