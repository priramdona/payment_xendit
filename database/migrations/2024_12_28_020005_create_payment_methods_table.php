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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code');
            $table->string('type');
            $table->decimal('min',14,4)->nullable();
            $table->decimal('max',14,4)->nullable();
            $table->boolean('status');
            $table->text('image');
            $table->string('source');
            $table->string('action')->default('deeplink');
            $table->string('reference')->nullable();
            $table->string('fee_type_1')->nullable();
            $table->decimal('fee_value_1',14,4)->nullable();
            $table->string('fee_type_2')->nullable();
            $table->decimal('fee_value_2',14,4)->nullable();
            $table->boolean('is_ppn')->default(true);
            $table->string('payment_process')->default('instant');
            $table->integer('expired')->default(1);
            $table->integer('settlement')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
