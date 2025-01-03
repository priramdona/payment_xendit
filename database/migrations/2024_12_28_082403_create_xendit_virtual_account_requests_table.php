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
        Schema::create('xendit_virtual_account_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('xen_virtual_account_id');
            $table->string('external_id');
            $table->string('owner_id');
            $table->string('bank_code');
            $table->string('merchant_code');
            $table->string('account_number');
            $table->string('name');
            $table->decimal('expected_amount',14,2);
            $table->boolean('is_single_use')->default(true);
            $table->string('is_closed')->default(true);
            $table->dateTime('expiration_date')->nullable();
            $table->string('status');
            $table->string('currency');
            $table->string('country');
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
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
        Schema::dropIfExists('xendit_virtual_account_requests');
    }
};
