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
        Schema::create('business_plans', function (Blueprint $table) {
            $table->id();
            $table->string('business_plan_id')->unique();
            $table->string('business_id');
            $table->foreign('business_id')->references('business_id')->on('businesses');
            $table->json('business_service_ids'); // Array of service IDs
            $table->string('plan_name');
            $table->text('plan_description')->nullable();
            $table->decimal('plan_price', 15, 2);
            $table->integer('duration_months'); // 1, 3, 6, 12 months
            $table->string('duration_label'); // "1 month", "3 months", "6 months", "1 year"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_plans');
    }
};
