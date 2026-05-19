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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('shipping_city')->nullable();
            $table->string('shipping_district')->nullable();
            $table->string('shipping_division')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('coupon_code')->nullable();
            $table->enum('status', [
                'pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'refunded'
            ])->default('pending');
            $table->enum('payment_method', ['cod', 'bkash', 'nagad', 'sslcommerz'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->string('courier')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->nullable()->comment('facebook, website, messenger');
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_number');
            $table->index('user_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
