<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notas_cuaderno', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ID_Alumno')->unique();
            $table->date('Fecha');
            $table->float('Nota', 52);
            $table->timestamps();

            $table->foreign('ID_Alumno')
                ->references('id_usuario')->on('alumno')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notas_cuaderno');
    }
};
