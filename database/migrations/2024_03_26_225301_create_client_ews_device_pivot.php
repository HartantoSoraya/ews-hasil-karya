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
        Schema::create('client_ews_device_pivot', function (Blueprint $table) {
            $table->id();

            $table->uuid('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->uuid('ews_device_id');
            $table->foreign('ews_device_id')->references('id')->on('ews_devices')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_ews_device_pivot');
    }
};
