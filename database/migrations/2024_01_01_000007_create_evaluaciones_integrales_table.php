<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evaluaciones_integrales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('responsabilidad', 4, 2)->default(0);
            $table->decimal('puntualidad', 4, 2)->default(0);
            $table->decimal('trabajo_en_equipo', 4, 2)->default(0);
            $table->decimal('comunicacion', 4, 2)->default(0);
            $table->decimal('respeto', 4, 2)->default(0);
            $table->decimal('compromiso', 4, 2)->default(0);
            $table->decimal('liderazgo', 4, 2)->default(0);
            $table->decimal('adaptabilidad', 4, 2)->default(0);
            $table->decimal('autonomia', 4, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('evaluaciones_integrales'); }
};
