<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // テーマの下に属する分類（概念・定義、背景・歴史など）を管理するテーブル
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // どのテーマに属するカテゴリかを紐付ける
            $table->foreignId('theme_id')->constrained('themes')->onDelete('cascade');
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
