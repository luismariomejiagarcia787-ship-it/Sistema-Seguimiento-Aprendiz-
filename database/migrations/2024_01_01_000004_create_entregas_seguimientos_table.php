<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// H8: Se eliminó completamente el módulo de entregas
// Se mantiene seguimientos para compatibilidad futura
return new class extends Migration
{
    public function up(): void
    {
        // Seguimientos (comentarios de progreso)
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('porcentaje', 5, 2)->default(0);
            $table->text('comentario');
            $table->date('fecha_seguimiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
