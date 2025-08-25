<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        SchoolProfile::create([
            'school_name' => 'SMA Negeri 1 Jakarta',
            'address' => 'Jl. Pendidikan No. 123, Jakarta Pusat',
            'phone' => '021-12345678',
            'email' => 'info@sman1jakarta.sch.id',
            'description' => 'Sekolah unggulan yang mengutamakan prestasi akademik dan karakter siswa.',
            'vision' => 'Menjadi sekolah terdepan dalam menghasilkan lulusan yang berkarakter, berprestasi, dan siap menghadapi tantangan global.',
            'mission' => 'Menyelenggarakan pendidikan berkualitas dengan mengintegrasikan teknologi modern dan nilai-nilai karakter.',
            'operational_hours' => 'Senin - Jumat: 07:00 - 15:00 WIB',
        ]);
    }
}