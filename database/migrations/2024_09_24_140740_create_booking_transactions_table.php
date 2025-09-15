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
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('booking_transaction_id')->unique();
            $table->string('payment_id')->unique()->nullable();
            $table->string('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('booking_id');
            $table->foreign('booking_id')->references('booking_id')->on('bookings');
            $table->string('description')->nullable();
            $table->string('payment_gateway_name')->nullable();
            $table->string('transaction_currency');
            $table->decimal('transaction_total', 15, 2);
            $table->string('transaction_date');
            $table->bigInteger('invoice_number')->nullable();
            $table->string('invoice_prefix')->nullable();
            $table->json('invoice_details')->nullable();
            $table->enum('transaction_status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_transactions');
    }
};
