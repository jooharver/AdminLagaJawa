<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('komunitas', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('image_logo')->nullable();
            $table->string('image_banner')->nullable();
            $table->string('phone');
            $table->string('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komunitas');
    }
};
