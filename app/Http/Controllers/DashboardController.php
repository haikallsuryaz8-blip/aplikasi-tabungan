<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetTabungan;
use App\Models\TransaksiCelengan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $targets = TargetTabungan::with('transaksi')->orderBy('status', 'asc')->orderBy('tenggat_waktu', 'asc')->get();

        $totalTerkumpul = $targets->sum('saldo_terkumpul');
        $totalTargetNominal = $targets->sum('target_nominal');
        $totalAktif = $targets->where('status', 'aktif')->count();
        $totalTercapai = $targets->where('status', 'tercapai')->count();

        $persentaseTotal = $totalTargetNominal > 0 ? round(($totalTerkumpul / $totalTargetNominal) * 100, 1) : 0;

        // Smart Recommendation Goal (prioritas: aktif & paling dekat deadline)
        $priorityGoal = $targets->where('status', 'aktif')->where('sisa_nominal', '>', 0)->sortBy('sisa_hari')->first();

        // 5 Transaksi Terakhir
        $transaksiTerbaru = TransaksiCelengan::with('target')->orderBy('tanggal_transaksi', 'desc')->orderBy('id_transaksi', 'desc')->take(6)->get();

        // Data Chart Donut: Distribusi per Kategori
        $kategoriStats = TargetTabungan::select('kategori', DB::raw('SUM(saldo_terkumpul) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori')
            ->toArray();

        // Data Chart Tren: 6 Bulan Terakhir
        $bulanLabels = [];
        $trenSetor = [];
        $trenTarik = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $bulanLabels[] = $date->translatedFormat('M Y');
            
            $setor = TransaksiCelengan::whereYear('tanggal_transaksi', $date->year)
                ->whereMonth('tanggal_transaksi', $date->month)
                ->where('jenis_transaksi', 'SETOR')
                ->sum('nominal');

            $tarik = TransaksiCelengan::whereYear('tanggal_transaksi', $date->year)
                ->whereMonth('tanggal_transaksi', $date->month)
                ->where('jenis_transaksi', 'TARIK')
                ->sum('nominal');

            $trenSetor[] = (float) $setor;
            $trenTarik[] = (float) $tarik;
        }

        return view('dashboard.index', compact(
            'targets',
            'totalTerkumpul',
            'totalTargetNominal',
            'totalAktif',
            'totalTercapai',
            'persentaseTotal',
            'priorityGoal',
            'transaksiTerbaru',
            'kategoriStats',
            'bulanLabels',
            'trenSetor',
            'trenTarik'
        ));
    }
}
