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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); 
            $table->foreign('user_id')->references('user_id')->on('users'); 
            $table->string('business_id')->unique();
            $table->string('business_name');
            $table->text('business_description')->nullable();
            $table->string('business_category_id');
            $table->foreign('business_category_id')->references('business_category_id')->on('business_categories');
            $table->string('business_cover_image_url')->nullable();
            $table->string('business_logo_url')->nullable();
            $table->string('business_website_url')->nullable();
            $table->string('business_email');
            $table->string('business_phone');
            $table->text('business_address');
            $table->string('business_country');
            $table->string('business_state');
            $table->string('business_city');
            $table->string('tax_number')->nullable();
            $table->longText('offline_transaction_details')->nullable();
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
        Schema::dropIfExists('businesses');
    }
};
