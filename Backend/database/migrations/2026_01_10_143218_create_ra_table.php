<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ra', function (Blueprint $table) {
            $table->id(); // Autoincremental
            $table->text('Descripcion'); // Text es mejor para descripciones largas
            
            // CLAVE FORÁNEA FUNDAMENTAL
            $table->foreignId('ID_Asignatura')
                  ->constrained('asignatura') // Apunta a la tabla 'asignatura'
                  ->onDelete('cascade');      // Si borras asignatura, se borran sus RAs
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ra');
    }
};