<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('booking_events', function (Blueprint $table) {
            $table->renameColumn("from_status", "status");
            $table->dropColumn("to_status");
        });
    }

    public function down(): void
    {
        Schema::table('booking_events', function (Blueprint $table) {
            $table->renameColumn("status", "from_status");
            $table->string("to_status")->nullable();
        });
    }
};
