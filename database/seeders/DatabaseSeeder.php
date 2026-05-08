<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\RiwayatPelanggaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Guru Tatib (Admin)',
            'email' => 'tatib@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'guru_tatib'
        ]);

        $pelanggaran = [
            ['nama_pelanggaran' => 'Terlambat Datang', 'poin_pelanggaran' => 5],
            ['nama_pelanggaran' => 'Atribut Tidak Lengkap (Dasi/Topi/Sabuk)', 'poin_pelanggaran' => 10],
            ['nama_pelanggaran' => 'Rambut Panjang / Diwarnai', 'poin_pelanggaran' => 15],
            ['nama_pelanggaran' => 'Membolos / Keluar Tanpa Izin', 'poin_pelanggaran' => 20],
            ['nama_pelanggaran' => 'Tidak Membawa ID Card', 'poin_pelanggaran' => 10],
        ];
        foreach ($pelanggaran as $p) {
            Pelanggaran::create($p);
        }

        Siswa::create([
            'rfid_uid' => '1234567890', 
            'nisn' => '0011223344',
            'nama' => 'Rafi Andi',
            'kelas' => 'XI RPL 2',
            'total_poin_pelanggaran' => 0
        ]);

        Siswa::create([
            'rfid_uid' => '0987654321', 
            'nisn' => '0055667788',
            'nama' => 'Habl Sankrumi',
            'kelas' => 'XI RPL 2',
            'total_poin_pelanggaran' => 0
        ]);

        Siswa::create([
            'rfid_uid' => '0042324880', 
            'nisn' => '0055667787',
            'nama' => 'Ghatfan Prayoga',
            'kelas' => 'XI RPL 2',
            'total_poin_pelanggaran' => 0
        ]);

         Siswa::create([
            'rfid_uid' => '0040911894', 
            'nisn' => '0055667789',
            'nama' => 'Jeki Nur Hakim',
            'kelas' => 'XI RPL 2',
            'total_poin_pelanggaran' => 0
        ]);
    }
}
