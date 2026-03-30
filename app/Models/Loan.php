<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'kode_eksemplar',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',

        // tambahan baru
        'is_extended',
        'denda'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'is_extended' => 'boolean'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
