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
            $table->foreignId('transaction_id')->constrained('transactions', 'id_transaction')->onDelete('cascade');
            $table->foreignId('court_id')->constrained('courts', 'id_court')->onDelete('cascade');
            $table->string('no_pemesanan')->unique();
            $table->date('booking_date');
            $table->json('time_slots');
            $table->integer('duration')->nullable();
            $table->bigInteger('amount');
            $table->bigInteger('total_amount')->default(0);
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
