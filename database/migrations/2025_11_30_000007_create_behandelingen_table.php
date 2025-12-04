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
        Schema::create('behandelingen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medewerker_id')->constrained('medewerkers')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patienten')->onDelete('cascade');
            $table->date('datum');
            $table->time('tijd');
            $table->enum('behandeling_type', ['Controles', 'Vullingen', 'Gebitsreiniging', 'Orthodontie', 'Wortelkanaalbehandelingen']);
            $table->text('omschrijving')->nullable();
            $table->decimal('kosten', 10, 2)->nullable();
            $table->enum('status', ['Behandeld', 'Onbehandeld', 'Uitgesteld'])->default('Onbehandeld');
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
        Schema::dropIfExists('behandelingen');
    }
};
