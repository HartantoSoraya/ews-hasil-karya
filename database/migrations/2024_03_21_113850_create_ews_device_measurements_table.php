<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ews_device_measurements', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('ews_device_id');
            $table->foreign('ews_device_id')->references('id')->on('ews_devices');
            $table->decimal('vibration_value', 30, 8)->default(0);
            $table->decimal('db_value', 30, 8)->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ews_device_measurements');
    }
};
