<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'judul',
        'pengarang',
        'edisi',
        'isbn_issn',
        'tahun_terbit',
        'tempat_terbit',
        'deskripsi_fisik',
        'bahasa',

        'no_panggil',
        'kode_inventaris',
        'lokasi',
        'lokasi_rak',
        'eksemplar',

        'gambar'
    ];
}
