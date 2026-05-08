<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Inertia\Inertia;

class PortalController extends Controller
{
    public function index()
    {
        return Inertia::render('Portal/Index');
    }

    public function searchSiswa(Request $request)
    {
        $keyword = $request->keyword;

        if (!$keyword) {
            return response()->json([]);
        }

        $siswaList = Siswa::where('nisn', $keyword)
            ->orWhere('nama', 'like', '%' . $keyword . '%')
            ->orderBy('nama', 'asc')
            ->get();

        return response()->json($siswaList);
    }

    public function getDetailSiswa($id)
    {
        $siswa = Siswa::with(['riwayatPelanggarans' => function ($query) {
            $query->latest();
        }, 'riwayatPelanggarans.pelanggaran', 'riwayatPelanggarans.user'])
        ->findOrFail($id);

        return response()->json($siswa);
    }
}
