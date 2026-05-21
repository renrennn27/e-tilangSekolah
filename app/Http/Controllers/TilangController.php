<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Pelanggaran;
use App\Models\RiwayatPelanggaran;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;

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

        try {
            $connector = new FilePrintConnector("/dev/usb/lp0");
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setTextSize(2, 2); 
            $printer->text("BUKTI E-TILANG\n");
            $printer->setTextSize(1, 1); 
            $printer->text("Sistem Kedisiplinan Sekolah\n");
            $printer->text("------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Tanggal : " . date('d-m-Y H:i') . "\n");
            $printer->text("Nama    : " . $siswa->nama . "\n"); 
            $printer->text("NISN    : " . $siswa->nisn . "\n");
            $printer->text("------------------------------------------\n");
            
            $printer->text("Pelanggaran:\n");
            $printer->text($pelanggaran->nama_pelanggaran . "\n");
            if ($request->catatan) {
                $printer->text("Ket     : " . $request->catatan . "\n");
            }
            $printer->text("------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("POIN DIBERIKAN : +" . $pelanggaran->poin_pelanggaran . "\n");
            $printer->text("TOTAL POIN     : " . $siswa->fresh()->total_poin_pelanggaran . "\n");
            $printer->text("------------------------------------------\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Harap simpan bukti ini\n");
            $printer->text("untuk pengambilan kembali barang yang disita.\n");

            $printer->feed(3);
            $printer->cut();
            $printer->close();

        } catch (\Exception $e) {
            \Log::error("Gagal Print Struk: " . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pelanggaran berhasil dicatat',
            'siswa' => clone $siswa->refresh(),
            'pelanggaran' => $pelanggaran,
            ]);
    }
}
