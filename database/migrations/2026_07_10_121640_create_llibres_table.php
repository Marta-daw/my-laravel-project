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
        Schema::create('llibres', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->integer('páginas');
            $table->longText('sinopsis');
            $table->foreignId('idioma_id')->constrained();
            $table->enum('leido', ['Sí', 'No', 'En progreso'])->default('No');
            $table->tinyInteger('puntuación')->nullable();
            $table->timestamps();
        });

        //Restricción de puntuación CHECK para un rango entre 0 y 5 (ambos incluidos)
        DB::statement('ALTER TABLE llibres ADD CONSTRAINT check_puntuación CHECK (puntuación BETWEEN 0 AND 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llibres');
    }
};
