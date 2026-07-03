<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('aprendices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('documento')->unique();
            $table->string('telefono')->nullable();
            $table->string('programa_formacion');
            $table->string('ficha');
            $table->date('fecha_inicio');
            $table->enum('estado', ['activo', 'inactivo', 'egresado', 'retirado'])->default('activo');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('aprendices'); }
};
