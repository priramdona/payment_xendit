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
        Schema::create('xendit_disbursement_channels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('xdm_id')->references('id')->on('xendit_disbursement_methods')->after('id');
            $table->string('name');
            $table->string('type');
            $table->string('code');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xendit_disbursement_channels');
    }
};
