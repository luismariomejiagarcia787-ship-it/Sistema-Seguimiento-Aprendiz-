<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aprendiz_id')->constrained('aprendices')->onDelete('cascade');
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('nota', 4, 2)->default(0)->comment('0.00 a 10.00');
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->unique(['aprendiz_id', 'actividad_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('calificaciones'); }
};
