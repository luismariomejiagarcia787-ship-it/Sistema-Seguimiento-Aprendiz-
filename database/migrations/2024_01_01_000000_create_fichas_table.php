<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->string('programa_formacion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'terminado'])->default('activo');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('fichas'); }
};
