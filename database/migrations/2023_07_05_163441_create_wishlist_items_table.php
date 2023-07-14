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
            $table->text("name");
            $table->text("brand")->nullable();
            $table->text("price")->nullable();
            $table->text("url")->nullable();
            $table->text("comment")->nullable();
            $table->text("image")->nullable();
            $table->integer("needs")->default(1);
            $table->integer("has")->default(0);
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
