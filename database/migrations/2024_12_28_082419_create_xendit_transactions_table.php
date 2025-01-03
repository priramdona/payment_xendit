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
        Schema::create('xendit_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_id')->unique();
            $table->string('product_id')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('channel_category')->nullable();
            $table->string('channel_code')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('account_identifier')->nullable();
            $table->string('currency')->nullable();
            $table->decimal('amount',14,2)->default(0);
            $table->decimal('xendit_fee',14,2)->default(0);
            $table->decimal('value_added_tax',14,2)->default(0);
            $table->decimal('xendit_withholding_tax',14,2)->default(0);
            $table->decimal('third_party_withholding_tax',14,2)->default(0);
            $table->string('fee_status')->nullable();
            $table->decimal('net_amount',14,2)->default(0);
            $table->string('cashflow')->nullable();
            $table->string('settlement_status')->nullable();
            $table->dateTime('estimated_settlement_time')->nullable();
            $table->string('business_id')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->json('fee')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_transactions');
    }
};
