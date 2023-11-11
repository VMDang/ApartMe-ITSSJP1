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
        Schema::create('request_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receive_user_id')
                ->constrained(table: 'users', indexName: 'FK_request_user_receive_user_id');
            $table->foreignId('request_id')
                ->constrained(table: 'requests', indexName: 'FK_requests_request_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_user');
    }
};
