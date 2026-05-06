<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_pelanggaran',
        'poin_pelanggaran',
    ];

    public function riwayatPelanggarans()
    {
        return $this->hasMany(RiwayatPelanggaran::class);
    }
}
