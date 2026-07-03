<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_limite');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'retrasada'])->default('pendiente');
            $table->integer('porcentaje_peso')->default(0)->comment('Peso en porcentaje para calculo de progreso');
            $table->timestamps();
        });

        // Tabla pivote aprendiz - actividad
        Schema::create('actividad_aprendiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'retrasada'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividad_aprendiz');
        Schema::dropIfExists('actividades');
    }
};
