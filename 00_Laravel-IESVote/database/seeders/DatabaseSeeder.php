<?php

namespace Database\Seeders; // Define la carpeta donde se guarda este archivo (database/seeders)

// IMPORTACIONES: Traemos los modelos y herramientas necesarios para que el Seeder pueda crear los datos
use App\Models\User;                  // Importa el modelo de Usuarios para crear las cuentas
use App\Models\Category;              // Importa el modelo de Categorías (Profesores, Alumnos, Padres)
use App\Models\Poll;                  // Importa el modelo de Encuestas (aquí llamadas Poll)
use App\Models\Option;                // Importa el modelo de Opciones para las respuestas de la encuesta
use Illuminate\Database\Seeder;       // Importa la clase base de Laravel que da las funciones de Seeder
use Illuminate\Support\Facades\Hash; // Importa la herramienta de encriptación segura para las contraseñas

class DatabaseSeeder extends Seeder
{
    /**
     * El método 'run' contiene las órdenes de ejecución.
     * Todo lo que pongas aquí dentro se meterá en la base de datos al ejecutar el comando.
     */
    public function run(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // 1. CREACIÓN DE LAS CATEGORÍAS OBLIGATORIAS
        // ══════════════════════════════════════════════════════════════════════
        // Creamos los tres grupos del colegio exigidos en los requisitos y los guardamos en variables
        $teacherCategory = Category::create(['name' => 'Teachers']); // Grupo de Profesores
        $studentCategory = Category::create(['name' => 'Students']); // Grupo de Alumnos
        $parentCategory = Category::create(['name' => 'Parents']);  // Grupo de Padres


        // ══════════════════════════════════════════════════════════════════════
        // 2. CREACIÓN DEL USUARIO ADMINISTRADOR
        // ══════════════════════════════════════════════════════════════════════
        // Creamos la cuenta del jefe del sistema para poder gestionar la web desde el principio
        $admin = User::create([
            'username' => 'admin',                   // Nombre de usuario para el Login
            'dni' => '00000000A',               // DNI de acceso del administrador
            'password' => Hash::make('admin123'),    // Encripta la contraseña 'admin123' para que no sea visible
            'is_admin' => true,                      // Activa el permiso especial de Administrador (true)
        ]);


        // ══════════════════════════════════════════════════════════════════════
        // 3. CREACIÓN DE UN ALUMNO NORMAL DE PRUEBA
        // ══════════════════════════════════════════════════════════════════════
        // Creamos un usuario común (alumno) con sus datos correspondientes
        $studentUser = User::create([
            'username' => 'andres_student',
            'dni' => '12345678X',
            'password' => Hash::make('password123'), // Contraseña encriptada segura
            'is_admin' => false,                     // NO es administrador (usuario común)
        ]);

        // REGLA: Con 'attach()' metemos al alumno dentro del grupo 'Students' en la tabla intermedia
        $studentUser->categories()->attach($studentCategory->id);


        // ══════════════════════════════════════════════════════════════════════
        // 4. CREACIÓN DE UN USUARIO ESPECIAL (DOBLE CATEGORÍA)
        // ══════════════════════════════════════════════════════════════════════
        // Creamos un usuario de prueba para el caso especial del PDF (Profesor y Padre a la vez)
        $multiUser = User::create([
            'username' => 'joan_teacher_parent',
            'dni' => '87654321Z',
            'password' => Hash::make('password123'),
            'is_admin' => false,                     // Tampoco es administrador
        ]);

        // REGLA: Usamos 'attach()' pasándole un array con los dos IDs [Teachers, Parents]
        // para que este usuario quede vinculado a ambos grupos a la vez y probar el voto compuesto.
        $multiUser->categories()->attach([$teacherCategory->id, $parentCategory->id]);


        // ══════════════════════════════════════════════════════════════════════
        // 5. CREACIÓN DE UNA ENCUESTA DE MUESTRA
        // ══════════════════════════════════════════════════════════════════════
        // Creamos una votación inicial para comprobar que toda la estructura funciona bien en la web
        $samplePoll = Poll::create([
            'title' => 'Consell Escolar 2026', // Título de la votación
            'description' => 'Official digital voting process for the school council.', // Descripción
            'type' => 'single_option',         // Tipo: Voto estándar de selección única (solo 1 opción)
            'is_real_time_enabled' => true,                   // Permite ver los resultados en vivo mientras se vota
            'is_anonymous' => true,                   // Voto secreto (oculta quién votó a qué)
            'is_active' => true,                   // La encuesta se crea abierta y disponible para votar
        ]);


        // ══════════════════════════════════════════════════════════════════════
        // 6. CREACIÓN DE LAS OPCIONES DE RESPUESTA PARA LA ENCUESTA
        // ══════════════════════════════════════════════════════════════════════
        // Añadimos las alternativas que los usuarios verán en la pantalla para poder votar
        Option::create([
            'poll_id' => $samplePoll->id, // Vincula esta opción a la encuesta 'Consell Escolar 2026' usando su ID
            'option_text' => 'Candidate A (Proposed by Teachers Association)', // Nombre del Candidato A
        ]);

        Option::create([
            'poll_id' => $samplePoll->id,
            'option_text' => 'Candidate B (Proposed by Parents Association)',
        ]);
    }
}
