<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Labakas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LabakasController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index(Request $request)
    {
        $data = Labakas::latest('created_at')->get();

        $balance = Labakas::sum('debet') - Labakas::sum('credit');
        $yayasan = Labakas::sum('yayasan');
        $kepala = Labakas::sum('kepala');
        $karyawan = Labakas::sum('karyawan');
        return view('labakas.index', compact('balance', 'data', 'yayasan', 'kepala', 'karyawan'));
    }
    public function create()
    {
        return view('labakas.create');
    }
    public function store(Request $request)
    {

        $tanggal = $request->tanggal;
        $Labakas = $request->kas;
        $ket = $request->ket;
        $nominal = $request->nominal;
        if ($Labakas == '0') {
            $data = [
                'tanggal' => $tanggal,
                'yayasan' => -$nominal,
                'debet' => 0,
                'credit' => $nominal,
                'ket' => $ket
            ];
            $simpan = DB::table('Labakas')->insert($data);
        }
        if ($Labakas == '1') {
            $data = [
                'tanggal' => $tanggal,
                'kepala' => -$nominal,
                'debet' => '0',
                'credit' => $nominal,
                'ket' => $ket
            ];
            $simpan = DB::table('labakas')->insert($data);
        }
        if ($Labakas == '2') {
            $data = [
                'tanggal' => $tanggal,
                'karyawan' => -$nominal,
                'debet' => '0',
                'credit' => $nominal,
                'ket' => $ket
            ];
            $simpan = DB::table('labakas')->insert($data);
        }

        if ($simpan) {
            return redirect('labakas')->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return redirect('labakas')->with(['success' => 'Data Gagal Disimpan']);
        }
    }
    public function show(Request $request, $id)
    {
        $data = Labakas::where('id', $id)
            ->get();
        $karyawan = Karyawan::where('jabatan', '!=', 'IT')->get();
        return view('labakas.show', compact('data', 'karyawan'));
    }
    public function edit($id)
    {
        $kas = Labakas::find($id);

        if (!$kas) {
            return redirect('/labakas')->with(['error' => 'Data tidak ditemukan']);
        }

        return view('labakas.edit', compact('kas'));
    }

    public function update(Request $request, $id)
    {
        $LabakasRecord = Labakas::find($id);

        if (!$LabakasRecord) {
            return redirect('/labakas')->with(['error' => 'Data tidak ditemukan']);
        }

        $tanggal = $request->tanggal;
        $LabakasType = $request->kas; // Use a different variable name
        $ket = $request->ket;
        $nominal = $request->nominal;

        // Periksa apakah data debit atau kredit yang akan diupdate
        if ($LabakasType == 'd') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => $nominal,
                'credit' => '0',
                'ket' => $ket
            ];
        } elseif ($LabakasType == 'c') {
            $updateData = [
                'tanggal' => $tanggal,
                'debet' => '0',
                'credit' => $nominal,
                'ket' => $ket
            ];
        } else {
            return redirect('/labakas')->with(['error' => 'Labakas harus berupa "d" atau "c"']);
        }

        // Lakukan update data
        $updated = DB::table('labakas')->where('id', $id)->update($updateData);

        if ($updated) {
            return redirect('/labakas')->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return redirect('/labakas')->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    public function destroy($id)
    {
        $Labakas = Labakas::find($id);
        $Labakas->delete();

        return redirect('/labakas')
            ->with('success', 'Labakas berhasil dihapus!');
    }
}
