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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaction');
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('no_pemesanan')->unique(); // kode unik per transaksi
            $table->enum('payment_method', ['qris', 'transfer', 'cod'])->default('transfer');
            $table->bigInteger('total_amount')->default(0);
            $table->enum('payment_status', ['waiting', 'paid', 'failed'])->default('waiting');
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
