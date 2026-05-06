<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\RiwayatPelanggaran;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class TilangController extends Controller
{
    public function index()
    {
        $pelanggarans = Pelanggaran::all();
        return Inertia::render('Kiosk', [
            'pelanggarans' => $pelanggarans
        ]);
    }

    public function searchSiswa(Request $request)
    {
        $keyword = $request->keyword; 
        $siswa = Siswa::where('rfid_uid', $keyword)
            ->orWhere('nisn', $keyword)
            ->orWhere('nama', 'like', '%' . $keyword . '%')
            ->get();

        return response()->json($siswa);
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'pelanggaran_id' => 'required|exists:pelanggarans,id',
            'catatan' => 'nullable|string',
            'foto_base64' => 'required',
        ]);

        $image_parts = explode(";base64,", $request->foto_base64);
        $image_base64 = base64_decode($image_parts[1]);
        $filename = 'tilang/' . uniqid() . '.png';

        Storage::disk('public')->put($filename, $image_base64);


        $pelanggaran = Pelanggaran::find($request->pelanggaran_id);
        $siswa = Siswa::find($request->siswa_id);

        RiwayatPelanggaran::create([
            'siswa_id' => $siswa->id,
            'pelanggaran_id' => $pelanggaran->id,
            'user_id' => auth()->id() ?? 1,
            'catatan' => $request->catatan,
            'foto_bukti' => $filename,
        ]);

        $siswa->increment('total_poin_pelanggaran', $pelanggaran->poin_pelanggaran);

        return response()->json([
            'status' => 'success',
            'message' => 'Pelanggaran berhasil dicatat',
            'siswa' => clone $siswa->refresh(),
            'pelanggaran' => $pelanggaran,
            ]);
    }
}
