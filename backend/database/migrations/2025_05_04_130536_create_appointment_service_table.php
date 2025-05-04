<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentServiceTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_service', function (Blueprint $table) {
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->onDelete('cascade');
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade');
            $table->primary(['appointment_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_service');
    }
}
