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
        Schema::create('holydays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID użytkownika, do którego należy urlop');
            $table->foreignId('holyday_type_id')
                ->constrained('holyday_types')
                ->onDelete('cascade')
                ->comment('ID typu urlopu');
            $table->datetime('start_date')->comment('Data rozpoczęcia urlopu');
            $table->datetime('end_date')->comment('Data zakończenia urlopu');
            $table->integer('hours')->default(0)->comment('Liczba godzin urlopu');
            $table->string('description')->nullable()->comment('Opis urlopu');
            $table->integer('approved')->default(0)->comment('Czy urlop został zatwierdzony');
            $table->boolean('paid')->default(false)->comment('Czy urlop jest płatny');
            $table->boolean('active')->default(true)->comment('Czy urlop jest aktywny');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holydays');
    }
};
