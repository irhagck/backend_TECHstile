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
        Schema::create('attendences', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->nullable();

            $table->string('type', 50)->nullable();

            $table->timestamp('timestamp')->useCurrent();

            $table->unsignedBigInteger('production_id')->nullable();

            $table->timestamps();

            // Optional foreign keys (recommended if tables exist)

            /*
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();

            $table->foreign('production_id')
                  ->references('id')
                  ->on('productions')
                  ->nullOnDelete();
            */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendences');
    }
};