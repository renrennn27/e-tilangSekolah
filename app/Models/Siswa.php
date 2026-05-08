<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function riwayatPelanggarans()
    {
        return $this->hasMany(RiwayatPelanggaran::class);
    }
}
