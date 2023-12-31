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
        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->foreignUuid('wishlist_id')
                ->references('id')
                ->on('wishlists')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('name');
            $table->text('brand')->nullable();
            $table->text('price')->nullable();
            $table->text('url')->nullable();
            $table->text('comment')->nullable();
            $table->text('image')->nullable();
            $table->boolean('unlimited_needs')->default(false);
            $table->integer('needs')->default(1);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_items');
    }
};
