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
        Schema::create('event_change_key_user_ids', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('foruserid')->nullable();
            $table->string('keyprivate')->nullable();
            $table->string('keypublic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_change_key_user_ids');
    }
};
