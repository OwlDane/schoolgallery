<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pengumuman',
                'description' => 'Berisi pengumuman resmi sekolah seperti jadwal ujian, libur, dan penerimaan siswa baru',
                'order' => 1,
            ],
            [
                'name' => 'Event & Acara',
                'description' => 'Kegiatan dan acara sekolah seperti MPLS, pentas seni, dan bazar',
                'order' => 2,
            ],
            [
                'name' => 'Artikel & Edukasi',
                'description' => 'Artikel pendidikan, tips belajar, motivasi, dan cerita inspiratif',
                'order' => 3,
            ],
            [
                'name' => 'Kesehatan & Lingkungan',
                'description' => 'Informasi seputar kesehatan, tips hidup sehat, dan kegiatan pelestarian lingkungan',
                'order' => 4,
            ],
            [
                'name' => 'Berita Alumni',
                'description' => 'Aktivitas, prestasi, dan kabar terbaru dari para alumni',
                'order' => 5,
            ]
        ];

        foreach ($categories as $category) {
            DB::table('news_categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'order' => $category['order'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
