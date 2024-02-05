<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }
    public function create()
    {
        return view('produk.create');
    }
    public function store(Request $request)
    {
        try {
            $produk = $request->produk;
            $satuan = $request->satuan;
            $beli = $request->beli;
            $jual = $request->jual;
            // $password = Hash::make('123456');

            // Check if the employee with the same produk already exists
            $existingproduk = DB::table('produks')->where('produk', $produk)->first();
            if ($existingproduk) {
                return redirect()->back()->with('error', 'Data dengan produk ' . $produk . ' sudah ada.');
            }

            $foto = null;
            if ($request->hasFile('foto')) {
                $foto = $produk . "." . $request->file('foto')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/produk/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            $data = [
                'produk' => $produk,
                'satuan' => $satuan,
                'jual' => $jual,
                'beli' => $beli,
                'foto' => $foto,

            ];

            $simpan = DB::table('produks')->insert($data);
            if ($simpan) {
                return redirect('/produk')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan produk " . $produk . " sudah digunakan";
            } else {
                $message = $e->getMessage();
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }
    public function show()
    {
        $produk = Produk::all();
        return view('produk.show', compact('produk'));
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $produk = DB::table('produks')->where('id', $id)->first();
        return view('produk.edit', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'produk' => 'required',
            'satuan' => 'required',
            'beli' => 'required',
            'jual' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Ambil data produk berdasarkan ID
            $produk = Produk::findOrFail($id);

            // Ambil nilai input

            $produkNama = $request->produk;
            $satuan = $request->satuan;
            $beli = $request->beli;
            $jual = $request->jual;

            // Proses foto
            if ($request->hasFile('foto')) {
                $foto = $produkNama . "." . $request->file('foto')->getClientOriginalExtension();

                // Hapus foto lama
                $folderPathOld = "public/uploads/produk/" . $produk->foto;
                Storage::delete($folderPathOld);

                // Simpan foto baru
                $folderPath = "public/uploads/produk/";
                $request->file('foto')->storeAs($folderPath, $foto);
            } else {
                $foto = $produk->foto;
            }

            // Data yang akan diupdate
            $data = [
                'produk' => $produkNama,
                'satuan' => $satuan,
                'jual' => $jual,
                'beli' => $beli,
                'foto' => $foto,
            ];

            // Lakukan update menggunakan Eloquent
            $produk->update($data);

            return redirect('/produk')->with('success', 'Data Berhasil di Update');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with(['error' => 'Data Gagal di Update']);
        }
    }
    public function createstok()
    {
        $produk = Produk::all();
        return view('produk.createstok', compact('produk'));
    }
    public function updatestok(Request $request)
    {
        // Validasi input
        $request->validate([
            'produk' => 'required', // Sesuaikan dengan aturan validasi yang diperlukan
            'stok' => 'required|numeric|min:0', // Sesuaikan dengan aturan validasi yang diperlukan
        ]);

        try {

            $produkId = $request->produk;
            $stokBaru = $request->stok;

            // Ambil data produk berdasarkan ID
            $produk = Produk::findOrFail($produkId);

            // Tambahkan stok baru ke stok yang sudah ada
            $stokLama = $produk->stok;
            $stokTotal = $stokLama + $stokBaru;

            // Data yang akan diupdate
            $data = [
                'stok' => $stokTotal,
            ];

            // Lakukan update menggunakan Eloquent
            $produk->update($data);
            $tanggal = $request->tanggal;

            $insertkas = [
                'tanggal' => $tanggal,
                'beli' => $produk->id,
                'jual' => '-',
                'produk' =>  $produk->produk,
                'debet' => 0,
                'credit' => $stokBaru * $produk->beli,
                'ket' => 'tambah stok' . ' ' . $produk->produk

            ];
            DB::beginTransaction();

            try {
                DB::table('kas')->insert($insertkas);
                if ($insertkas) {
                    DB::commit();

                    return redirect('/produk.show')->with('success', 'Stok berhasil ditambahkan');
                } else {
                    throw new \Exception('insert modal data failed');
                }
            } catch (\Exception $e) {
                DB::rollBack();

                $message = ($e->getCode() == 23000) ? "Data gagal Disimpan" : $e->getMessage();
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
            }
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with(['error' => 'Data Gagal di Update']);
        }
    }



    public function destroy($id)
    {
        try {
            // Temukan data produk berdasarkan ID
            $produk = Produk::findOrFail($id);

            // Hapus foto terkait
            $folderPath = "public/uploads/produk/";
            $fotoPath = $folderPath . $produk->foto;
            Storage::delete($fotoPath);

            // Hapus data produk dari database
            $produk->delete();

            return redirect('/produk')->with('success', 'Data Berhasil di Hapus');
        } catch (\Exception $e) {
            // Tangani kesalahan
            return redirect()->back()->with(['error' => 'Data Gagal di Hapus']);
        }
    }
}
