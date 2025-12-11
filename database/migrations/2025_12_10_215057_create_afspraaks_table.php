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
        Schema::create('afspraken', function (Blueprint $table) {
            $table->id();

            // Verwijzing naar de gebruiker (patiënt)
            $table->foreignId('patient_id')
                ->constrained('users')
                ->onDelete('cascade');

            // Datum en tijden van de afspraak
            $table->date('datum');
            $table->time('starttijd');
            $table->time('eindtijd')->nullable();

            // Timestamps (aangemaakt & bijgewerkt)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afspraken');
    }
};
