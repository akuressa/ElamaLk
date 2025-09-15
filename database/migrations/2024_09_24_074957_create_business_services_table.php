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
        Schema::create('business_services', function (Blueprint $table) {
            $table->id(); 
            $table->string('business_id');
            $table->foreign('business_id')->references('business_id')->on('businesses');
            $table->string('business_service_id')->unique();            
            $table->string('business_category_id');
            $table->foreign('business_category_id')->references('business_category_id')->on('business_categories');
            $table->string('business_service_name');
            $table->string('business_service_slug');
            $table->text('business_service_description')->nullable();
            $table->integer('time_duration');
            $table->json('service_slots');
            $table->decimal('amount', 15, 2);
            $table->timestamp('promotion_exp_at')->nullable();
            $table->json('business_employee_ids');
            $table->integer('status')->default(1);          
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });      
               
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_services');
    }
};
