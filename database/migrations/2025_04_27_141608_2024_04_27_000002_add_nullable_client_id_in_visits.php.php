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
        // Check if column exists first, to decide what to do
        if (Schema::hasColumn('visits', 'client_id')) {
            // Remove foreign key constraint and update column to be nullable
            Schema::table('visits', function (Blueprint $table) {
                $table->dropForeign(['client_id']);
                // Modify instead of drop and recreate
                $table->foreignId('client_id')->nullable()->change();
                // Re-add the constraint with cascade
                $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            });
        } else {
            // Create the column if it doesn't exist
            Schema::table('visits', function (Blueprint $table) {
                $table->foreignId('client_id')->nullable()->constrained('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};
