<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'is_published',
        'admin_id',
        'kategori_id', // pastikan ada kolom ini di tabel galleries
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
