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
        Schema::create('business_employees', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('business_id');
            $table->foreign('business_id')->references('business_id')->on('businesses');
            $table->string('business_employee_id')->unique();
            $table->string('business_employee_name');
            $table->string('business_employee_email');
            $table->string('business_employee_phone')->nullable();
            $table->json('permissions')->nullable(); // JSON or serialized array of permissions
            $table->integer('status')->default(1);
            $table->boolean('is_login')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });       
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_employees');
    }
};
