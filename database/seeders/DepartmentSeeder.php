<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed the default departments.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'BSIT',
                'department_head' => 'Department Head - BSIT',
                'description' => 'Bachelor of Science in Information Technology department.',
            ],
            [
                'name' => 'BSCS',
                'department_head' => 'Department Head - BSCS',
                'description' => 'Bachelor of Science in Computer Science department.',
            ],
            [
                'name' => 'BSEd',
                'department_head' => 'Department Head - BSEd',
                'description' => 'Bachelor of Secondary Education department.',
            ],
            [
                'name' => 'BSBA',
                'department_head' => 'Department Head - BSBA',
                'description' => 'Bachelor of Science in Business Administration department.',
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                [
                    'department_head' => $department['department_head'],
                    'description' => $department['description'],
                ]
            );
        }
    }
}