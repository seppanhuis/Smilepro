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
        Schema::create('communicaties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patienten')->onDelete('cascade');
            $table->foreignId('medewerker_id')->constrained('medewerkers')->onDelete('cascade');
            $table->text('bericht');
            $table->timestamp('verzonden_datum');
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
        Schema::dropIfExists('communicaties');
    }
};
