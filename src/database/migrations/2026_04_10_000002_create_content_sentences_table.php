<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 文章テーブルを新規作成
        Schema::create('content_sentences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->enum('type', ['reference', 'opinion']);
            $table->text('value');
            $table->string('url', 500)->nullable();
            $table->string('url_title', 200)->nullable();
            $table->unsignedTinyInteger('sort_order')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_sentences');
    }
};
