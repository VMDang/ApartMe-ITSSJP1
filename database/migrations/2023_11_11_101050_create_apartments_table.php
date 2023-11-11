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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('address', 255);
            $table->string('owner_email')->unique();
            $table->integer('number_floors', false, true);
            $table->integer('number_rooms', false, true);
            $table->integer('area', false, true);
            $table->foreignId('apartment_type_id')
                ->constrained(table: 'apartment_types', indexName: 'FK_apartments_apartment_type_id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
            $table->foreign('owner_email', 'FK_apartments_owner_email')
                ->references('owner_email')->on('registrations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
