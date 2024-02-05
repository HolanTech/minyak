<?php

namespace App\Http\Controllers;

use App\Models\Jual;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        return view('report.index', compact('namabulan'));
    }
    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Tambahkan filter berdasarkan bulan dan tahun
        $histori = Jual::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->join('produks', 'produks.produk', '=', 'juals.produk')
            ->select('juals.*', 'produks.satuan as satuan')
            ->orderBy('juals.created_at', 'desc')
            ->get();

        return view('report.gethistori', compact('histori'));
    }
}
