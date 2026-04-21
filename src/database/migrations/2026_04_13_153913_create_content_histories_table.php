<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // 親のid(user_id)が削除されたら、この外部キーを削除しないかわりにnullにする
            $table->unsignedInteger('history_number');
            $table->enum('action', ['created', 'updated', 'deleted']);
            $table->string('title', 200)->nullable();
            $table->json('sentences');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('content_histories');
    }
};
