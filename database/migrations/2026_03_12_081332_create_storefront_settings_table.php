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
        Schema::create('storefront_settings', function (Blueprint $table) {
            $table->id();
            $table->string('banner_title')->nullable();
            $table->text('short_description')->nullable();
            $table->string('main_image')->nullable();

            // Using foreignId makes the relationship explicit at the database level
            $table->foreignId('featured_1')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('featured_2')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('featured_3')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('featured_4')->nullable()->constrained('products')->nullOnDelete();
            $table->string('featured_badge_1')->nullable()->default('Featured');
            $table->string('featured_badge_2')->nullable()->default('Best Seller');
            $table->string('featured_badge_3')->nullable()->default('Eco Friendly');
            $table->string('featured_badge_4')->nullable()->default('New Arrival');
            $table->string('banner_badge')->nullable()->default('Our Collection');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storefront_settings');
    }
};
