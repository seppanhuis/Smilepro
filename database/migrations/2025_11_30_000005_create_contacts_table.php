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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patienten')->onDelete('cascade');
            $table->string('straatnaam');
            $table->string('huisnummer');
            $table->string('toevoeging')->nullable();
            $table->string('postcode');
            $table->string('plaats');
            $table->string('mobiel')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('contacts');
    }
};
