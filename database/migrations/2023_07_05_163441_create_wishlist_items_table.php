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
            $table->id();
            $table->timestamps();

            $table->foreignId('wishlist_id')->constrained("wishlists")
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text("title");
            $table->text("price");
            $table->text("url")->nullable();
            $table->text("comment")->nullable();
            $table->text("image")->nullable();
            $table->integer("needs")->default(1);
            $table->boolean("purchased")->default(false);
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
