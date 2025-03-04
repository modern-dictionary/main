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
        Schema::create('word_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('word_id')->unsigned();
            $table->unsignedBiginteger('category_id')->unsigned();

            $table->foreign('word_id')->references('id')
                ->on('words')->onDelete('cascade');
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('word_category');
    }
};
