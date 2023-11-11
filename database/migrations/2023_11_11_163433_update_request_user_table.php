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
        Schema::table('request_user', function (Blueprint $table) {
            $table->dropForeign('FK_request_user_receive_user_id');
            $table->dropColumn('receive_user_id');
            $table->foreignId('user_id')->after('id')
                ->constrained(table: 'users', indexName: 'FK_request_user_user_id');
            $table->boolean('is_owner')->after('request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_user', function (Blueprint $table) {
            $table->dropColumn('is_owner');
            $table->dropForeign('FK_request_user_user_id');
            $table->dropColumn('user_id');
            $table->foreignId('receive_user_id')
                ->constrained(table: 'users', indexName: 'FK_request_user_receive_user_id');
        });
    }
};
