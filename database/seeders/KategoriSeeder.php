<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Berita Sekolah',
                'deskripsi' => 'Berita seputar kegiatan sekolah',
                'is_active' => true
            ],
            [
                'nama' => 'Pengumuman',
                'deskripsi' => 'Pengumuman resmi dari sekolah',
                'is_active' => true
            ],
            [
                'nama' => 'Kegiatan Siswa',
                'deskripsi' => 'Berbagai kegiatan ekstrakurikuler siswa',
                'is_active' => true
            ],
            [
                'nama' => 'Prestasi',
                'deskripsi' => 'Prestasi yang diraih oleh siswa dan sekolah',
                'is_active' => true
            ],
            [
                'nama' => 'Artikel Pendidikan',
                'deskripsi' => 'Artikel seputar dunia pendidikan',
                'is_active' => true
            ]
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
