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
        Schema::create('xendit_callback_payment_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('callback_id')->nullable();
            $table->string('reference_id');
            $table->json('data')->nullable();
            $table->string('event');
            $table->string('status');
            $table->string('failure_code')->nullable();
            $table->string('xen_business_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_callback_payment_requests');
    }
};
