<?php

namespace App\Http\Controllers;

use App\Models\Jual;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JualController extends Controller
{
    public function index()
    {
        $jual = Jual::join('produks', 'produks.produk', '=', 'juals.produk')
            ->select('juals.*', 'produks.satuan as satuan')
            ->orderBy('juals.created_at', 'desc')
            ->get();

        return view('jual.index', compact('jual'));
    }

    public function create()
    {
        $produk = Produk::all();
        return view('jual.create', compact('produk'));
    }
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal' => 'required',
            'produk' => 'required',
            'stok' => 'required|numeric|min:0',
        ]);

        try {
            $tanggal = $request->tanggal;
            $produkId = $request->produk;
            $stokJual = $request->stok;

            // Ambil data produk berdasarkan ID
            $produk = Produk::findOrFail($produkId);

            // Cek apakah stok mencukupi
            if ($produk->stok < $stokJual) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk penjualan');
            }
            $untung = $produk->jual - $produk->beli;
            $laba = $untung * $stokJual;
            $yayasan = $laba * 50 / 100;
            $kepala = $laba * 30 / 100;
            $karyawan = $laba * 20 / 100;
            // Data penjualan
            $jual = [
                'tanggal' => $tanggal,
                'produk' => $produk->produk,
                'qty' => $stokJual,
                'harga' => $stokJual * $produk->jual,
                'laba' => $laba,
                'yayasan' => $yayasan,
                'kepala' => $kepala,
                'karyawan' => $karyawan,
            ];

            // Simpan data penjualan
            $jualRecord = Jual::create($jual);

            // Data transaksi kas
            $insertkas = [
                'tanggal' => $tanggal,
                'beli' => '-',
                'jual' => $jualRecord->id,
                'produk' => $produk->produk,
                'debet' => $stokJual * $produk->beli,
                'credit' => 0,
                'ket' => 'Jual stok  ' . $produk->produk . ' ' . $stokJual . ' ' . $produk->satuan,
            ];
            $insertlabakas = [

                'tanggal' => $tanggal,
                'jual' => $jualRecord->id,
                'laba' => $laba,
                'yayasan' => $yayasan,
                'kepala' => $kepala,
                'karyawan' => $karyawan,

                'debet' => $laba,
                'credit' => 0,
                'ket' => 'Keuntungan Penjualan  ' . $produk->produk . ' ' . $stokJual . ' ' . $produk->satuan,
            ];

            // Kurangi stok produk
            $stokLama = $produk->stok;
            $stokTotal = $stokLama - $stokJual;

            // Data yang akan diupdate
            $updatestok = [
                'stok' => $stokTotal,
            ];

            DB::beginTransaction();

            try {
                // Simpan data transaksi kas
                DB::table('kas')->insert($insertkas);
                DB::table('labakas')->insert($insertlabakas);

                // Update stok produk
                Produk::where('id', $produkId)->update($updatestok);

                DB::commit();

                return redirect('/jual')->with('success', 'Data Penjualan berhasil ditambahkan');
            } catch (\Exception $e) {
                DB::rollBack();

                $message = ($e->getCode() == 23000) ? "Data gagal Disimpan" : $e->getMessage();
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            // Ambil data penjualan berdasarkan ID
            $jual = Jual::findOrFail($id);

            DB::beginTransaction();

            try {
                // Hapus data transaksi kas terkait
                DB::table('kas')->where('jual', $id)->delete();
                DB::table('labakas')->where('jual', $id)->delete();

                // Kembalikan stok produk
                $produk = Produk::where('produk', $jual->produk)->first();
                $stokTotal = $produk->stok + $jual->qty;

                // Update stok produk
                Produk::where('id', $produk->id)->update(['stok' => $stokTotal]);

                // Hapus data penjualan
                $jual->delete();

                DB::commit();

                return redirect('/jual')->with('success', 'Data Penjualan berhasil dihapus');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
