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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // 🔥 Factory relation
            $table->unsignedBigInteger('factory_id');

            // 🔥 User relation (real employee user)
            $table->unsignedBigInteger('user_id');

            // Optional: keep for backward compatibility
            $table->string('employee_id')->nullable();

            // Shift timing
            $table->time('shift_starttime')->nullable();
            $table->time('shift_endtime')->nullable();

            // Record timestamp
            $table->timestamp('timestamp')->useCurrent();

            $table->timestamps();

            // 🔥 Foreign keys (IMPORTANT)
            $table->foreign('factory_id')
                ->references('id')
                ->on('factories')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};