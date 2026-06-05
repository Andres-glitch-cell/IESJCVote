<?php

namespace Database\Seeders; // Define la carpeta donde se guarda este archivo (database/seeders)

use App\Models\User;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Importa la herramienta de encriptación segura

class DatabaseSeeder extends Seeder
{
    /**
     * El método 'run' contiene las órdenes de ejecución.
     * Todo lo que pongas aquí dentro se meterá en la base de datos al ejecutar el comando.
     */
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 1. CREACIÓN DE LAS CATEGORÍAS OBLIGATORIAS
        // ══════════════════════════════════════════════════════════════════════
        // Creamos los tres grupos del colegio exigidos en los requisitos
        $teacherCategory = Category::create(['name' => 'Profesores']);
        $studentCategory = Category::create(['name' => 'Alumnos']);
        $parentCategory = Category::create(['name' => 'Padres']);


        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 2. CREACIÓN DEL USUARIO ADMINISTRADOR
        // ══════════════════════════════════════════════════════════════════════
        $admin = User::create([
            'username' => 'admin',
            'dni' => '00000000A',
            'password' => Hash::make('admin123'),    // Encripta la contraseña 'admin123'
            'is_admin' => true,                      // Activa el permiso de Administrador
        ]);


        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 3. CREACIÓN DE UN ALUMNO NORMAL DE PRUEBA
        // ══════════════════════════════════════════════════════════════════════
        $studentUser = User::create([
            'username' => 'andres_alumno',
            'dni' => '12345678X',
            'password' => Hash::make('password123'),
            'is_admin' => false,                     // NO es administrador
        ]);

        // REGLA: Con 'attach()' metemos al alumno dentro del grupo 'Alumnos' en la tabla intermedia
        $studentUser->categories()->attach($studentCategory->id);


        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 4. CREACIÓN DE UN USUARIO ESPECIAL (DOBLE CATEGORÍA)
        // ══════════════════════════════════════════════════════════════════════
        $multiUser = User::create([
            'username' => 'joan_profesor_padre',
            'dni' => '87654321Z',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);

        $multiUser->categories()->attach([$teacherCategory->id, $parentCategory->id]);


        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 5. CREACIÓN DE UNA ENCUESTA DE MUESTRA
        // ══════════════════════════════════════════════════════════════════════
        // Creamos una votación inicial para comprobar que todo funciona
        $samplePoll = Poll::create([
            'title' => 'Consell Escolar 2026',
            'description' => 'Proceso de votación digital oficial para el consejo escolar.',
            'type' => 'single_option',       // Tipo: Selección única
            'is_real_time_enabled' => true,                 // Resultados en vivo
            'is_anonymous' => true,                 // Voto secreto
            'is_active' => true,                 // Encuesta abierta
        ]);


        // ══════════════════════════════════════════════════════════════════════
        // IMPORTANTE 6. CREACIÓN DE LAS OPCIONES DE RESPUESTA
        // ══════════════════════════════════════════════════════════════════════
        Option::create([
            'poll_id' => $samplePoll->id,
            'option_text' => 'Candidato A (Propuesto por la Asociación de Profesores)',
        ]);

        Option::create([
            'poll_id' => $samplePoll->id,
            'option_text' => 'Candidato B (Propuesto por la Asociación de Padres)',
        ]);
    }
}
