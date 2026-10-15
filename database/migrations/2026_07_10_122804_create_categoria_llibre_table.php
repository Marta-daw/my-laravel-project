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
        Schema::create('categoria_llibre', function (Blueprint $table) {
            //clave forana de categoria
            $table->foreignId('categoria_id')
                ->nullable() // Permet que el llibre es quedi sense categoria
                ->constrained()
                ->onDelete('set null') // Si s'esborra la categoria, posa aquest camp a NULL
                ->cascadeOnUpdate();

            //clave forana de libro
            $table->foreignId('llibre_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            
            $table->unique(['categoria_id', 'llibre_id']); // Evita duplicats
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categoria_llibre');
    }
};
