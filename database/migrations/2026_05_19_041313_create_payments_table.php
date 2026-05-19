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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->enum('method', ['cod', 'bkash', 'nagad', 'sslcommerz']);
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('BDT');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
