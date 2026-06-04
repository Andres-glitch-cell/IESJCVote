<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Poll;
use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create the 3 mandatory core categories from the PDF
        $teacherCategory = Category::create(['name' => 'Teachers']);
        $studentCategory = Category::create(['name' => 'Students']);
        $parentCategory = Category::create(['name' => 'Parents']);

        // 2. Create the Administrator User
        $admin = User::create([
            'username' => 'admin',
            'dni' => '00000000A',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
        ]);

        // 3. Create a regular student user
        $studentUser = User::create([
            'username' => 'andres_student',
            'dni' => '12345678X',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);
        // Attach student to the Students category
        $studentUser->categories()->attach($studentCategory->id);

        // 4. Create a special user with MULTIPLE categories (Teacher + Parent exception)
        $multiUser = User::create([
            'username' => 'joan_teacher_parent',
            'dni' => '87654321Z',
            'password' => Hash::make('password123'),
            'is_admin' => false,
        ]);
        // Attach to both categories to test the compound voting rule
        $multiUser->categories()->attach([$teacherCategory->id, $parentCategory->id]);


        // 5. Create a sample Poll to test the application structure
        $samplePoll = Poll::create([
            'title' => 'Consell Escolar 2026',
            'description' => 'Official digital voting process for the school council.',
            'type' => 'single_option', // Standard 1 selection poll
            'is_real_time_enabled' => true,
            'is_anonymous' => true,
            'is_active' => true,
        ]);

        // 6. Create sample options for this poll
        Option::create([
            'poll_id' => $samplePoll->id,
            'option_text' => 'Candidate A (Proposed by Teachers Association)',
        ]);

        Option::create([
            'poll_id' => $samplePoll->id,
            'option_text' => 'Candidate B (Proposed by Parents Association)',
        ]);
    }
}
