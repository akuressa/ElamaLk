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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_id')->unique();
            $table->string('plan_name');
            $table->string('plan_description');
            $table->json('plan_features');
            $table->double('plan_price', 15, 2);
            $table->integer('plan_validity')->default(31);
            $table->boolean('is_trial')->default(0);
            $table->boolean('is_private')->default(0);
            $table->integer('is_recommended')->default(0);
            $table->integer('is_customer_support')->default(0);           
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
        Schema::dropIfExists('plans');
    }
};
