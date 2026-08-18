<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('name_en');
            $table->string('name_fa');
            $table->string('material');
            $table->string('summary_en');
            $table->text('description_en')->nullable();
            $table->json('applications')->nullable();
            $table->json('specs')->nullable();
            $table->string('size_range')->nullable();
            $table->string('length_range')->nullable();
            $table->string('pressure')->nullable();
            $table->boolean('food_grade')->default(false);
            $table->boolean('medical_grade')->default(false);
            $table->boolean('featured')->default(false);
            $table->enum('availability', ['In stock', 'Made to order', 'On request'])->default('On request');
            $table->unsignedInteger('catalog_page')->nullable();
            $table->timestamps();

            $table->index(['category_id', 'featured']);
            $table->index('availability');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
