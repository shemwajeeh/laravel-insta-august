<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('post_caption_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete(); // posts.id
            $table->string('lang', 10)->index(); // 'en','ja','ko','zh' など
            $table->text('text');
            $table->timestamps();

            $table->unique(['post_id', 'lang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_caption_translations');
    }
};
