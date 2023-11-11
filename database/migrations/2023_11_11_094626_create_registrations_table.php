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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('owner_email')->unique();
            $table->string('password');
            $table->string('phone', 255);
            $table->string('address', 255);
            $table->integer('number_floors', false, true);
            $table->integer('number_rooms', false, true);
            $table->integer('area', false, true);
            $table->tinyInteger('approve_status')->default(0);
            $table->string('approve_reason', 255)->nullable();
            $table->foreignId('apartment_type_id')
                ->constrained(table: 'apartment_types', indexName: 'FK_registrations_apartment_type_id')
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
        Schema::dropIfExists('registrations');
    }
};
