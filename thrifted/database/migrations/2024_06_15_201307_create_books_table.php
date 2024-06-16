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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->string('isbn')->nullable();
            $table->string('edition')->nullable();
            $table->string('condition');
            $table->boolean('new')->default(false);
            $table->unsignedBigInteger('price')->default(0);
            $table->string('format');
            $table->unsignedBigInteger('old_price')->nullable();
            $table->string('available')->default(true);            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
