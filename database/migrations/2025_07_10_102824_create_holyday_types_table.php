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
        Schema::create('holyday_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Nazwa typu urlopu');
            $table->string('description')->nullable()->comment('Opis typu urlopu');
            $table->boolean('active')->default(true)->comment('Czy typ urlopu jest aktywny');
            $table->boolean('is_paid')->default(false)->comment('Czy urlop jest płatny');
            // -1 oznacza brak limitu godzin, 0 urlop brany z puli dni urlopu
            $table->integer('hours')->default(0)->comment('Liczba godzin urlopu');
            $table->string('color')->default('#000000')->comment('Kolor reprezentujący typ urlopu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holyday_types');
    }
};
