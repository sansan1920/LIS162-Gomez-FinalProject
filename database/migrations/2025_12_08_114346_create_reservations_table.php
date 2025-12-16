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
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigIncrements('reservation_id');
            $table->date('reservation_date');
            $table->string('reservation_status', 20)->default('Approved');
            $table->timestamp('approval_date')->useCurrent();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('schedule_id');
            $table->unsignedBigInteger('feedback_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')
                  ->references('id') 
                  ->on('users')
                  ->onDelete('cascade');
            $table->foreign('schedule_id')
                  ->references('schedule_id')
                  ->on('schedules')
                  ->onDelete('restrict');
            $table->foreign('feedback_id')
                  ->references('feedback_id')
                  ->on('feedbacks')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
