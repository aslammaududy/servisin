<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropColumn('price');
            $table->dropColumn('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->integer('service_id')->nullable();
            $table->integer('price')->nullable();
            $table->integer('quantity')->nullable();
        });
    }
};
