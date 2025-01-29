<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();  // نام دسته‌بندی (مثلاً "ورزشی")
            $table->string('slug')->unique();  // اسلاگ برای SEO و لینک‌سازی بهتر
            $table->text('description')->nullable(); // توضیحات دسته‌بندی (اختیاری)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
