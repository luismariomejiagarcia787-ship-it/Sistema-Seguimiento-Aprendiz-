<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Aprendiz;
use App\Models\Ficha;
use App\Models\InstructorFicha;
use App\Models\Actividad;
use App\Models\Calificacion;
use App\Models\EvaluacionIntegral;
use App\Models\Observacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Administrador ────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@sena.edu.co'],
            ['name' => 'Administrador SENA', 'password' => Hash::make('Admin1234!'), 'rol' => 'administrador']
        );

        // ── Instructores ─────────────────────────────────────────
        $inst1 = User::updateOrCreate(
            ['email' => 'instructor1@sena.edu.co'],
            ['name' => 'Carlos Rodríguez', 'password' => Hash::make('Inst1234!'), 'rol' => 'instructor']
        );
        $inst2 = User::updateOrCreate(
            ['email' => 'instructor2@sena.edu.co'],
            ['name' => 'María López', 'password' => Hash::make('Inst1234!'), 'rol' => 'instructor']
        );

        // ── Fichas ────────────────────────────────────────────────
        $ficha1 = Ficha::updateOrCreate(
            ['numero' => '2850674'],
            [
                'programa_formacion' => 'Análisis y Desarrollo de Software',
                'fecha_inicio' => '2024-01-15',
                'fecha_fin'    => '2025-07-15',
                'estado'       => 'activo',
                'descripcion'  => 'Programa técnico en ADSI',
            ]
        );
        $ficha2 = Ficha::updateOrCreate(
            ['numero' => '2753421'],
            [
                'programa_formacion' => 'Técnico en Sistemas',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin'    => '2025-09-01',
                'estado'       => 'activo',
            ]
        );

        // ── Asignar instructores a fichas ─────────────────────────
        InstructorFicha::updateOrCreate(['user_id' => $inst1->id, 'ficha' => $ficha1->numero]);
        InstructorFicha::updateOrCreate(['user_id' => $inst2->id, 'ficha' => $ficha1->numero]);
        InstructorFicha::updateOrCreate(['user_id' => $inst1->id, 'ficha' => $ficha2->numero]);

        // ── Aprendices ────────────────────────────────────────────
        $aprendizData = [
            ['name'=>'Ana García',    'email'=>'ana@aprendiz.sena.edu.co',    'doc'=>'1001234567','ficha'=>$ficha1->numero],
            ['name'=>'Luis Pérez',    'email'=>'luis@aprendiz.sena.edu.co',   'doc'=>'1007654321','ficha'=>$ficha1->numero],
            ['name'=>'María Torres',  'email'=>'maria@aprendiz.sena.edu.co',  'doc'=>'1009876543','ficha'=>$ficha1->numero],
            ['name'=>'Juan Martínez', 'email'=>'juan@aprendiz.sena.edu.co',   'doc'=>'1002345678','ficha'=>$ficha2->numero],
        ];

        $aprendices = [];
        foreach ($aprendizData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('Apren1234!'), 'rol' => 'aprendiz']
            );
            $aprendices[] = Aprendiz::updateOrCreate(
                ['documento' => $data['doc']],
                [
                    'user_id'            => $user->id,
                    'programa_formacion' => $data['ficha'] === $ficha1->numero ? $ficha1->programa_formacion : $ficha2->programa_formacion,
                    'ficha'              => $data['ficha'],
                    'fecha_inicio'       => '2024-01-15',
                    'estado'             => 'activo',
                ]
            );
        }

        // ── Actividades (con aprendices auto-asignados) ────────────
        $actividadesData = [
            ['titulo'=>'Taller de POO','desc'=>'Programación orientada a objetos en Java','ficha'=>$ficha1->numero,'instructor'=>$inst1->id,'limite'=>'2024-06-30'],
            ['titulo'=>'Base de Datos Relacionales','desc'=>'Diseño y normalización de BD','ficha'=>$ficha1->numero,'instructor'=>$inst2->id,'limite'=>'2024-07-15'],
            ['titulo'=>'Proyecto Final','desc'=>'Desarrollo de aplicación web completa','ficha'=>$ficha1->numero,'instructor'=>$inst1->id,'limite'=>'2024-08-31'],
        ];

        foreach ($actividadesData as $ad) {
            $act = Actividad::updateOrCreate(
                ['titulo' => $ad['titulo'], 'ficha_asignada' => $ad['ficha']],
                [
                    'descripcion'     => $ad['desc'],
                    'instructor_id'   => $ad['instructor'],
                    'fecha_limite'    => $ad['limite'],
                    'estado'          => 'completada',
                    'porcentaje_peso' => 33,
                    'ficha_asignada'  => $ad['ficha'],
                ]
            );
            // Asignar aprendices de la ficha
            $aps = Aprendiz::where('ficha', $ad['ficha'])->pluck('id');
            foreach ($aps as $apId) {
                $act->aprendices()->syncWithoutDetaching([$apId => ['estado' => 'completada']]);
            }
        }

        // ── Calificaciones de ejemplo ────────────────────────────
        $notas = [8.5, 7.0, 9.0, 6.5, 8.0, 7.5, 9.5, 7.0, 8.0, 6.0, 7.5, 8.5];
        $idx   = 0;
        foreach (Actividad::where('ficha_asignada', $ficha1->numero)->get() as $act) {
            foreach (array_slice($aprendices, 0, 3) as $ap) {
                Calificacion::updateOrCreate(
                    ['aprendiz_id' => $ap->id, 'actividad_id' => $act->id],
                    ['instructor_id' => $act->instructor_id, 'nota' => $notas[$idx % count($notas)]]
                );
                $idx++;
            }
        }

        // ── Evaluaciones Integrales ───────────────────────────────
        foreach (array_slice($aprendices, 0, 3) as $ap) {
            foreach ([$inst1, $inst2] as $inst) {
                EvaluacionIntegral::updateOrCreate(
                    ['aprendiz_id' => $ap->id, 'instructor_id' => $inst->id],
                    [
                        'responsabilidad'   => rand(70, 95) / 10,
                        'puntualidad'       => rand(60, 90) / 10,
                        'trabajo_en_equipo' => rand(75, 95) / 10,
                        'comunicacion'      => rand(65, 90) / 10,
                        'respeto'           => rand(80, 100) / 10,
                        'compromiso'        => rand(70, 95) / 10,
                        'liderazgo'         => rand(60, 90) / 10,
                        'adaptabilidad'     => rand(70, 95) / 10,
                        'autonomia'         => rand(65, 90) / 10,
                        'observaciones'     => 'Evaluación integral registrada por seeder.',
                    ]
                );
            }
        }

        // ── Observaciones ─────────────────────────────────────────
        Observacion::updateOrCreate(
            ['aprendiz_id' => $aprendices[0]->id, 'instructor_id' => $inst1->id],
            ['contenido' => 'Excelente rendimiento académico y actitud positiva.', 'tipo' => 'academica']
        );
        Observacion::updateOrCreate(
            ['aprendiz_id' => $aprendices[1]->id, 'instructor_id' => $inst2->id],
            ['contenido' => 'Debe mejorar la puntualidad en la entrega de actividades.', 'tipo' => 'general']
        );

        $this->command->info('✅ Datos de prueba creados exitosamente.');
        $this->command->table(
            ['Rol', 'Email', 'Contraseña'],
            [
                ['Administrador', 'admin@sena.edu.co',        'Admin1234!'],
                ['Instructor',    'instructor1@sena.edu.co',  'Inst1234!'],
                ['Instructor',    'instructor2@sena.edu.co',  'Inst1234!'],
                ['Aprendiz',      'ana@aprendiz.sena.edu.co', 'Apren1234!'],
                ['Aprendiz',      'luis@aprendiz.sena.edu.co','Apren1234!'],
            ]
        );
    }
}
