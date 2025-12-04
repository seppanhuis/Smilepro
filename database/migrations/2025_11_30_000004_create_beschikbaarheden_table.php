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
        Schema::create('beschikbaarheden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medewerker_id')->constrained('medewerkers')->onDelete('cascade');
            $table->date('datum_vanaf');
            $table->date('datum_tot_met');
            $table->time('tijd_vanaf');
            $table->time('tijd_tot_met');
            $table->enum('status', ['Aanwezig', 'Afwezig', 'Verlof', 'Ziek'])->default('Aanwezig');
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
        Schema::dropIfExists('beschikbaarheden');
    }
};
