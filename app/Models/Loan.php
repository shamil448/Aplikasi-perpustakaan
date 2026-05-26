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
        'is_extended',
        'denda',
        'denda_dibayar',
        'tanggal_bayar',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_bayar' => 'datetime',
        'is_extended' => 'boolean',
        'denda' => 'integer',
        'denda_dibayar' => 'integer',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
