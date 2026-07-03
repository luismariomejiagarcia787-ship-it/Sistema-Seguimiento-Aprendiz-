<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            // Ficha de referencia (cuando se asigna a una ficha completa)
            $table->string('ficha_asignada')->nullable()->after('porcentaje_peso')
                  ->comment('Ficha a la que fue asignada globalmente, si aplica');
        });
    }

    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropColumn('ficha_asignada');
        });
    }
};
