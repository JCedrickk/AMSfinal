<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['name' => 'Computer Science', 'code' => 'BSCS', 'sort_order' => 1],
            ['name' => 'Information Technology', 'code' => 'BSIT', 'sort_order' => 2],
            ['name' => 'Computer Engineering', 'code' => 'BSCpE', 'sort_order' => 3],
            ['name' => 'Electronics Engineering', 'code' => 'BSECE', 'sort_order' => 4],
            ['name' => 'Business Administration', 'code' => 'BSBA', 'sort_order' => 5],
            ['name' => 'Accountancy', 'code' => 'BSA', 'sort_order' => 6],
            ['name' => 'Psychology', 'code' => 'AB Psych', 'sort_order' => 7],
            ['name' => 'Education', 'code' => 'BSEd', 'sort_order' => 8],
            ['name' => 'Nursing', 'code' => 'BSN', 'sort_order' => 9],
            ['name' => 'Architecture', 'code' => 'BS Arch', 'sort_order' => 10],
            ['name' => 'Civil Engineering', 'code' => 'BSCE', 'sort_order' => 11],
            ['name' => 'Mechanical Engineering', 'code' => 'BSME', 'sort_order' => 12],
            ['name' => 'Electrical Engineering', 'code' => 'BSEE', 'sort_order' => 13],
            ['name' => 'Communication', 'code' => 'AB Comm', 'sort_order' => 14],
            ['name' => 'Political Science', 'code' => 'AB PolSci', 'sort_order' => 15],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}