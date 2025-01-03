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
        Schema::create('xendit_payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('pm_id')->nullable();
            $table->string('business_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->foreignUuid('xendit_payment_request_id')->references('id')->on('xendit_payment_requests')->after('id');
            $table->string('type')->nullable();
            $table->string('country')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->text('description')->nullable();
            $table->string('reference_id')->nullable();
            $table->decimal('amount',14,2)->nullable();
            $table->decimal('transaction_amount',14,2)->nullable();
            $table->nullableUuidMorphs('transactional');
            $table->decimal('received_amount',14,4)->nullable();
            $table->decimal('deduction_amount',14,4)->nullable();
            $table->json('actions')->nullable();
            $table->json('card')->nullable();
            $table->json('direct_debit')->nullable();
            $table->json('ewallet')->nullable();
            $table->json('over_the_counter')->nullable();
            $table->json('virtual_account')->nullable();
            $table->json('qr_code')->nullable();
            $table->json('billing_information')->nullable();
            $table->string('reusability');
            $table->string('direct_bank_transfer')->nullable();
            $table->string('status');
            $table->string('failure_code')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_disbursement')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_payment_methods');
    }
};
