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
        Schema::create('xendit_callbacks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('xendit_payment_request_id')->constrained();
            $table->foreignUuid('xendit_payment_method_id')->constrained();
            $table->string('event');
            $table->string('status');
            $table->string('failure_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_callbacks');
    }
};
