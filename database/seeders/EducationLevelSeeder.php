<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Sekolah Dasar', 'code' => 'SD', 'min_grade' => 1, 'max_grade' => 6],
            ['name' => 'Sekolah Menengah Pertama', 'code' => 'SMP', 'min_grade' => 7, 'max_grade' => 9],
            ['name' => 'Sekolah Menengah Atas', 'code' => 'SMA', 'min_grade' => 10, 'max_grade' => 12],
            ['name' => 'Sekolah Menengah Kejuruan', 'code' => 'SMK', 'min_grade' => 10, 'max_grade' => 12],
            ['name' => 'Madrasah', 'code' => 'MADRASAH', 'min_grade' => 1, 'max_grade' => 12],
            ['name' => 'Pesantren', 'code' => 'PESANTREN', 'min_grade' => 1, 'max_grade' => 12],
            ['name' => 'Bimbingan Belajar', 'code' => 'BIMBEL', 'min_grade' => 1, 'max_grade' => 12],
        ];

        foreach ($levels as $level) {
            EducationLevel::firstOrCreate(['code' => $level['code']], $level);
        }
    }
}
