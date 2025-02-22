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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained("users")->onDelete('cascade');
            $table->foreignId('client_id')->constrained("users")->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->string('comment')->nullable();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->unsignedTinyInteger('rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
