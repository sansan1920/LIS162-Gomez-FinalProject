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
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('schedule_id');
            $table->string('day_of_week', 10);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('facility_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('facility_id')
                  ->references('facility_id')
                  ->on('facilities')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
