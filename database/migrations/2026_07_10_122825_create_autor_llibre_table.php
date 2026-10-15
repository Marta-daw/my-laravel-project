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
        Schema::create('autor_llibre', function (Blueprint $table) {
            //clave forana de autor
            $table->foreignId('autor_id')
                ->constrained('autors')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            //clave forana de libro
            $table->foreignId('llibre_id')
                ->constrained('llibres')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['autor_id', 'llibre_id']); // Evita duplicats
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autor_llibre');
    }
};
