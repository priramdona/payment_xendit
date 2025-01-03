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
        Schema::create('xendit_disbursements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('reference_id')->nullable();
            $table->string('disbursement_id')->nullable();
            $table->string('channel_code')->nullable();
            $table->json('channel_properties')->nullable();
            $table->decimal('amount', 14, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('currency')->nullable();
            $table->json('receipt_notification')->nullable();
            $table->json('metadata')->nullable();
            $table->dateTime('created')->nullable();
            $table->dateTime('updated')->nullable();
            $table->string('xen_business_id')->nullable();
            $table->string('status')->nullable();
            $table->string('failure_code')->nullable();
            $table->dateTime('estimated_arrival_time')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_disbursements');
    }
};
