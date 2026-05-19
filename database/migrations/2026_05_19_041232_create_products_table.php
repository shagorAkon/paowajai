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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->unique()->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->timestamp('flash_sale_start')->nullable();
            $table->timestamp('flash_sale_end')->nullable();
            $table->decimal('flash_sale_price', 10, 2)->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('sold_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('is_flash_sale');
            $table->index('price');
            $table->fullText(['name', 'short_description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
