<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            // Remaining length (in meters) at which the owner should get a low-stock alert.
            $table->decimal('alert_threshold', 10, 2)->nullable()->after('remaining');
            // Prevents sending the same alert again and again for the same batch/machine.
            $table->boolean('alert_sent')->default(false)->after('alert_threshold');
        });
    }

    public function down(): void
    {
        Schema::table('productions', function (Blueprint $table) {
            $table->dropColumn(['alert_threshold', 'alert_sent']);
        });
    }
};
