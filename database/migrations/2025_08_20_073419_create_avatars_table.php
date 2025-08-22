<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('avatars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('config');            // スライダーなどの設定（JSON）
            $table->string('preview_url')->nullable(); // サムネURL（任意）
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('avatars');
    }
};
