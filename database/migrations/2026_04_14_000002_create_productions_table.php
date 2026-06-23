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
        Schema::create('productions', function (Blueprint $table) {
            $table->id();

            $table->string('variety_type')->nullable();

            $table->decimal('total_length', 10, 2)->nullable();

            $table->integer('ready_production')->nullable();

            $table->integer('waste_production')->nullable();

            $table->integer('remaining')->nullable();

            $table->unsignedBigInteger('machine_id')->nullable();

            $table->unsignedBigInteger('employee_id')->nullable();

            $table->unsignedBigInteger('factory_id')->nullable();

            $table->unsignedBigInteger('manager_id')->nullable();

            $table->dateTime('shift_start')->nullable();

            $table->dateTime('shift_end')->nullable();

            $table->timestamps();

            // Foreign Keys (uncomment if these tables exist)

            /*
            $table->foreign('machine_id')
                  ->references('id')
                  ->on('machines')
                  ->nullOnDelete();

            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();

            $table->foreign('factory_id')
                  ->references('id')
                  ->on('factories')
                  ->nullOnDelete();

            $table->foreign('manager_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productions');
    }
};