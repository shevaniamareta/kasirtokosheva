<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $pelangganList = Pelanggan::all();

        return view('laporan.form', [
            'pelangganList' => $pelangganList,
        ]);
    }

    public function harian(Request $request)
    {
        $tanggal = $request->tanggal;
        $role = $request->role;

        $penjualan = Penjualan::leftJoin('users', 'users.id', '=', 'penjualans.user_id')
            ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->whereDate('penjualans.tanggal', $tanggal)
            ->when($role, function ($query) use ($role) {
                $query->where('users.role', $role);
            })
            ->select('penjualans.*', 'pelanggans.nama as nama_pelanggan', 'users.nama as nama_kasir')
            ->orderBy('penjualans.id')
            ->get();

        return view('laporan.harian', [
            'penjualan' => $penjualan,
            'tanggal' => $tanggal,
            'role' => $role,
        ]);
    }

    public function bulanan(Request $request)
    {
        $pelangganList = Pelanggan::all();
    
        $penjualan = Penjualan::join('detil_penjualans', 'detil_penjualans.penjualan_id', '=', 'penjualans.id')
    ->join('produks', 'produks.id', '=', 'detil_penjualans.produk_id')
    ->leftJoin('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
    ->select(
        DB::raw('MAX(pelanggans.nama) as nama_pelanggan'),
        DB::raw('COUNT(penjualans.id) as jumlah_transaksi'),
        DB::raw('SUM(penjualans.total) as jumlah_total'),
        DB::raw('DATE_FORMAT(penjualans.tanggal, "%d/%m/%Y") as tgl')
    )
    ->whereMonth('penjualans.tanggal', $request->bulan)
    ->whereYear('penjualans.tanggal', $request->tahun)
    ->where('penjualans.status', '!=', 'batal')
    ->when($request->pelanggan, function ($query) use ($request) {
        return $query->where('pelanggans.id', $request->pelanggan);
    })
    ->groupBy('tgl')
    ->get();

        $nama_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
    
        $bulan = isset($nama_bulan[$request->bulan]) ? $nama_bulan[$request->bulan] : null;
    
        return view('laporan.bulanan', [
            'penjualan' => $penjualan,
            'bulan' => $bulan,
            'pelangganList' => $pelangganList,
        ]);
    }
}    

