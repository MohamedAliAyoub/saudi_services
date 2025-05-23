<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('partner_services', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['partner_id']);

            // Add it back with cascade delete
            $table->foreign('partner_id')
                ->references('id')
                ->on('partners')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('partner_services', function (Blueprint $table) {
            // Revert to the original constraint without cascade
            $table->dropForeign(['partner_id']);

            $table->foreign('partner_id')
                ->references('id')
                ->on('partners');
        });
    }
};
