<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('booking_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('from_status');
            $table->string('to_status');
            $table->unsignedBigInteger('changed_by');
            $table->text('note');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_events');
    }
};
