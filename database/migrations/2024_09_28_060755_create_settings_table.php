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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_id')->unique();
            $table->longText('analytics_id')->nullable();
		    $table->longText('google_tag')->nullable();
            $table->longText('adsense_code')->nullable();
            $table->longText('site_name')->nullable();
            $table->longText('site_logo')->nullable();
            $table->longText('favicon')->nullable();
            $table->longText('tawk_chat_key')->nullable();
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
        Schema::dropIfExists('settings');
    }
};
