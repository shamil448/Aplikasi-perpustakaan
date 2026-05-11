<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberProfile extends Model
{
    protected $fillable = [
        'user_id',
        'nim_nidn',
        'fakultas',
        'jurusan',
        'nomor_hp',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
