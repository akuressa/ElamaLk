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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique();
            $table->string('user_id'); 
            $table->foreign('user_id')->references('user_id')->on('users'); 
            $table->string('business_id'); 
            $table->foreign('business_id')->references('business_id')->on('businesses'); 
            $table->string('business_service_id'); 
            $table->foreign('business_service_id')->references('business_service_id')->on('business_services');
            $table->string('business_employee_id'); 
            $table->foreign('business_employee_id')->references('business_employee_id')->on('business_employees');
            $table->string('booking_date');
            $table->string('booking_time');
            $table->string('phone_number')->nullable();
            $table->decimal('total_price', 15, 2);
            $table->integer('status')->default(1);
            $table->text('notes')->nullable();
            $table->integer('is_refund')->default(0);
            $table->longText('refund_message')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
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
