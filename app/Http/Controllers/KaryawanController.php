<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = DB::table("karyawans")->orderBy('nama_lengkap')
            // ->join('departemens', 'karyawans.kode_dept', '=', 'departemens.kode_dept')
            ->get();
        // $departemen = DB::table('departemens')->get();
        // $cabang = DB::table('cabangs')->orderBy('kode_cabang')->get();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $username = $request->username;
            $nama_lengkap = $request->nama_lengkap;
            $jabatan = $request->jabatan;
            $no_hp = $request->no_hp;
            $password = Hash::make('123456');

            // Check if the employee with the same username already exists
            $existingKaryawan = DB::table('karyawans')->where('username', $username)->first();
            if ($existingKaryawan) {
                return redirect()->back()->with('error', 'Data dengan username ' . $username . ' sudah ada.');
            }

            $foto = null;
            if ($request->hasFile('foto')) {
                $foto = $username . "." . $request->file('foto')->getClientOriginalExtension();

                // Store the photo
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }

            $data = [
                'username' => $username,
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'jabatan' => $jabatan,
                'foto' => $foto,
                'password' => $password,
            ];

            $simpan = DB::table('karyawans')->insert($data);
            if ($simpan) {
                return redirect('/karyawan')->with('success', 'Data berhasil disimpan');
            } else {
                return redirect()->back()->with('error', 'Data gagal disimpan');
            }
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                $message = "Data dengan username " . $username . " sudah digunakan";
            } else {
                $message = $e->getMessage();
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $message);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'username' => 'required',
            'nama_lengkap' => 'required',
            'jabatan' => 'required',
            'no_hp' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable|min:6',
        ]);

        // Ambil data karyawan berdasarkan ID
        $karyawan = DB::table('karyawans')->where('id', $id)->first();

        // Ambil nilai input
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = $request->password;

        // Proses foto
        if ($request->hasFile('foto')) {
            $foto = $username . "." . $request->file('foto')->getClientOriginalExtension();

            // Hapus foto lama
            $folderPathOld = "public/uploads/karyawan/" . $karyawan->foto;
            Storage::delete($folderPathOld);

            // Simpan foto baru
            $folderPath = "public/uploads/karyawan/";
            $request->file('foto')->storeAs($folderPath, $foto);
        } else {
            $foto = $karyawan->foto;
        }

        // Hash password jika diisi
        if (!empty($password)) {
            $password = bcrypt($password);
        }

        // Data yang akan diupdate
        $data = [
            'username' => $username,
            'nama_lengkap' => $nama_lengkap,
            'no_hp' => $no_hp,
            'jabatan' => $jabatan,
            'foto' => $foto,
            'password' => $password,
        ];

        try {
            // Lakukan update menggunakan Eloquent jika ada model Karyawan
            // Jika tidak, Anda dapat tetap menggunakan DB::table('karyawans')
            $update = Karyawan::where('id', $id)->update($data);

            if ($update) {
                return redirect('/karyawan')->with('success', 'Data Berhasil di Update');
            }
        } catch (\Exception $e) {
            // Tangani kesalahan
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }
    }

    public function editprofile()
    {
        $id = Auth::guard('karyawan')->user()->id;
        $karyawan = DB::table('karyawans')->where('id', $id)->first();
        return view('karyawan.editprofile', compact('karyawan'));
    }
    public function updateprofile(Request $request)
    {
        $id = Auth::guard('karyawan')->user()->id;
        $username = $request->username;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = bcrypt($request->password);

        $karyawan = DB::table('karyawans')->where('id', $id)->first();

        if ($request->hasFile('foto')) {
            $foto = $username . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        $data = [
            'username' => $username,
            'nama_lengkap' => $nama_lengkap,
            'no_hp' => $no_hp,
            'foto' => $foto,
        ];

        if (!empty($request->password)) {
            $data['password'] = $password;
        }

        $update = DB::table('karyawans')->where('id', $id)->update($data);

        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal di Update']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $karyawan = DB::table('karyawans')->where('id', $id)->first();

            if ($karyawan) {
                // Hapus foto yang tersimpan
                $folderPath = "public/uploads/karyawan/";
                Storage::delete($folderPath . $karyawan->foto);

                // Hapus record dari database
                $hapus = DB::table('karyawans')->where('id', $id)->delete();

                if ($hapus) {
                    return redirect()->back()->with('success', 'Data berhasil dihapus');
                }
            }

            return redirect()->back()->with('error', 'Data tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
