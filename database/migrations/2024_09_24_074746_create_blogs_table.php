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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('blog_id')->unique();
            $table->string('blog_category_id');
            $table->foreign('blog_category_id')->references('blog_category_id')->on('blog_categories');
            $table->string('blog_cover')->nullable();
            $table->string('blog_name');
            $table->string('blog_slug')->unique();
            $table->longText('short_description');
            $table->longText('long_description');
            $table->longText('tags')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('keywords');
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
        Schema::dropIfExists('blogs');
    }
};
