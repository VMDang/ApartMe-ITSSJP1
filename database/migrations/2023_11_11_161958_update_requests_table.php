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
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('FK_requests_create_user_id');
            $table->dropColumn('create_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table){
            $table->foreignId('create_user_id')
                ->constrained(table: 'users', indexName: 'FK_requests_create_user_id');
        });
    }
};
