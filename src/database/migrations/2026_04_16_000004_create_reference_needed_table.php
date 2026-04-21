<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference_needed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained('contents')->cascadeOnDelete();
            $table->foreignId('added_by')->constrained('users');   // 追加した管理者
            $table->timestamp('mail_sent_at')->nullable();           // メール送信日時
            $table->timestamp('question_mark_added_at')->nullable(); // ?付与日時
            $table->timestamps();

            $table->unique('content_id'); // 同じ内容を重複登録しない
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_needed');
    }
};
