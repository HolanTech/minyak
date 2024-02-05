<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use App\Models\Labakas;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::guard('karyawan')->check()) {
            $user = Auth::guard('karyawan')->user();
            return view('dashboard.dashboard', compact('user'));
        } else {
            // Handle ketika pengguna tidak login
            return redirect('/login');
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    // public function dashboardadmin()
    // {
    //     $hariini = date("Y-m-d");
    //     $rekappresensi = DB::table('presensis')
    //         ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:00",1,0)) as jmltelat')
    //         ->where('tgl_presensi', $hariini)
    //         ->first();
    //     $rekapizin = DB::table('izins')
    //         ->selectRaw('SUM(CASE WHEN status="i" THEN 1 ELSE 0 END) as jmlizin, SUM(CASE WHEN status="s" THEN 1 ELSE 0 END) as jmlsakit')
    //         ->where('tgl_izin', $hariini)
    //         ->where('approved', 1)
    //         ->first();

    //     return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin'));
    // }
    public function home(Request $request, Kas $kasModel, Labakas $labakasModel)
    {
        // Kaslabakas Data
        $monthlabakass = range(1, 12);
        $monthlabakasLabels = [];
        $debetlabakasTotals = [];
        $creditlabakasTotals = [];
        $balancelabakass = [];
        $currentBalancelabakas = 0;

        foreach ($monthlabakass as $monthlabakas) {
            $monthlabakasData = Labakas::select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('created_at', $monthlabakas)
                ->first();

            $debetlabakasTotals[] = $monthlabakasData->total_debet ?? 0;
            $creditlabakasTotals[] = $monthlabakasData->total_credit ?? 0;

            // Calculate the current balance for "Kaslabakas"
            $currentBalancelabakas += $debetlabakasTotals[$monthlabakas - 1] - $creditlabakasTotals[$monthlabakas - 1];
            $balancelabakass[] = $currentBalancelabakas;

            $monthlabakasLabels[] = Carbon::create()->month($monthlabakas)->format('F');
        }

        // Kas Data
        $monthkass = range(1, 12);
        $monthkasLabels = [];
        $debetkasTotals = [];
        $creditkasTotals = [];
        $balancekass = [];
        $currentBalancekas = 0;

        foreach ($monthkass as $monthkas) {
            $monthkasData = $kasModel
                ->select(DB::raw('SUM(debet) as total_debet'), DB::raw('SUM(credit) as total_credit'))
                ->whereMonth('tanggal', $monthkas)
                ->first();

            $debetkasTotals[] = $monthkasData->total_debet ?? 0;
            $creditkasTotals[] = $monthkasData->total_credit ?? 0;

            // Calculate the current balance for "Kaskas"
            $currentBalancekas += $debetkasTotals[$monthkas - 1] - $creditkasTotals[$monthkas - 1];
            $balancekass[] = $currentBalancekas;

            $monthkasLabels[] = Carbon::create()->month($monthkas)->format('F');
        }

        return view('dashboard.home', compact(
            'monthlabakasLabels',
            'debetlabakasTotals',
            'creditlabakasTotals',
            'balancelabakass',
            'monthkasLabels',
            'debetkasTotals',
            'creditkasTotals',
            'balancekass'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
}
