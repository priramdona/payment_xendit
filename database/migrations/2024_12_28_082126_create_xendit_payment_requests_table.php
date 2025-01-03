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
        Schema::create('xendit_payment_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('payment_request_id');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('reference_id');
            $table->string('customer_id')->nullable();
            $table->string('customer')->nullable();
            $table->string('business_id')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('min_amount', 14, 2)->nullable();
            $table->decimal('max_amount', 14, 2)->nullable();
            $table->string('country')->default('ID');
            $table->string('currency')->default('IDR');
            $table->json('payment_method');
            $table->text('description')->nullable();
            $table->string('failure_code')->nullable();
            $table->string('capture_method')->default('AUTOMATIC');
            $table->string('initiator')->nullable();
            $table->string('card_verification_results')->nullable();
            $table->string('status');
            $table->json('actions')->nullable();
            $table->json('metadata')->nullable();
            $table->json('shipping_information')->nullable();
            $table->json('items')->nullable();
            $table->json('paid_information')->nullable();
            $table->dateTime('transaction_timestamp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_payment_requests');
    }
};
