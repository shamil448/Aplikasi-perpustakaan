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
        'status'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
