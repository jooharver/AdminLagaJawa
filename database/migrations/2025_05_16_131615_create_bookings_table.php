<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id('id_booking');
            $table->foreignId('requester_id')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('court_id')->constrained('courts', 'id_court')->onDelete('cascade')->nullable();
            $table->string('no_pemesanan')->nullable();
            $table->date('booking_date');
            $table->json('time_slots');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration')->nullable();
            $table->enum('approval_status', ['pending', 'checked_in', 'cancelled'])->default('pending');
            $table->foreignId('payment_id')->nullable()->constrained('payments', 'id_payment')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
