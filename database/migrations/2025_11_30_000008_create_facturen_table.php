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
        Schema::create('facturen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patienten')->onDelete('cascade');
            $table->foreignId('behandeling_id')->constrained('behandelingen')->onDelete('cascade');
            $table->string('nummer')->unique();
            $table->date('datum');
            $table->decimal('bedrag', 10, 2);
            $table->enum('status', ['Verzonden', 'Niet-Verzonden', 'Betaald', 'Onbetaald'])->default('Niet-Verzonden');
            $table->boolean('is_actief')->default(true);
            $table->text('opmerking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturen');
    }
};
