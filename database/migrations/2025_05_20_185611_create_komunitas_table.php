<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('komunitas', function (Blueprint $table) {
            $table->id('id_komunitas');
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('image_logo')->nullable();
            $table->string('phone');
            $table->string('deskripsi');
            $table->date('booking_date')->nullable();
            $table->json('time_slots')->nullable();
            $table->string('court')->nullable();
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
