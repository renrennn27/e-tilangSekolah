<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPelanggaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'siswa_id',
        'pelanggaran_id',
        'user_id',
        'catatan',
        'foto_bukti',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pelanggaran()
    {
        return $this->belongsTo(Pelanggaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
