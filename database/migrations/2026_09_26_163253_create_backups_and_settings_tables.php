<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
   public function up(): void
{
    Schema::create('backups', function (Blueprint $table) {
        $table->id();
        $table->string('filename');
        $table->unsignedBigInteger('size')->default(0);
        $table->enum('type', ['manual', 'auto', 'pre_restore'])->default('manual');
        $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
        $table->text('error')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });

   
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('backups');
        Schema::dropIfExists('settings');
    }
};