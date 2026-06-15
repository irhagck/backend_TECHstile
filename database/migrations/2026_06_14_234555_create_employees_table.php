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

            $table->string('employee_id')->nullable();

            $table->time('shift_starttime')->nullable();
            $table->time('shift_endtime')->nullable();

            // This column exists in your DB but is NOT recommended in Laravel naming
            $table->timestamp('timestamp')->useCurrent();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();

            // Optional foreign key (recommended if users table exists)
            // $table->foreign('user_id')
            //       ->references('id')
            //       ->on('users')
            //       ->nullOnDelete();
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