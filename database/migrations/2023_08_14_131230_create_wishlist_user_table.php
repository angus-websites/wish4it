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
        Schema::create('wishlist_user', function (Blueprint $table) {
            $table->foreignUuid('wishlist_id')
                ->references('id')
                ->on('wishlists')
                ->onDelete('cascade');

            $table->foreignUuid('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            $table->enum('role', ['owner', 'collaborator'])->default('collaborator');;
            
            // Unique constraint for wishlist_id and user_id combination
            $table->unique(['wishlist_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_user');
    }
};
