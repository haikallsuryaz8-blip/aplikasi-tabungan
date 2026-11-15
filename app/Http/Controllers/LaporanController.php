<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetTabungan;
use App\Models\TransaksiCelengan;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiCelengan::with('target')->orderBy('tanggal_transaksi', 'desc');

        if ($request->filled('id_target')) {
            $query->where('id_target', $request->id_target);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tgl_mulai);
        }

        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tgl_selesai);
        }

        $transaksi = $query->get();
        $targets = TargetTabungan::orderBy('nama_target', 'asc')->get();

        $totalSetor = $transaksi->where('jenis_transaksi', 'SETOR')->sum('nominal');
        $totalTarik = $transaksi->where('jenis_transaksi', 'TARIK')->sum('nominal');
        $netSavings = $totalSetor - $totalTarik;

        return view('laporan.index', compact('transaksi', 'targets', 'totalSetor', 'totalTarik', 'netSavings'));
    }

    public function cetak(Request $request)
    {
        $query = TransaksiCelengan::with('target')->orderBy('tanggal_transaksi', 'asc');

        if ($request->filled('id_target')) {
            $query->where('id_target', $request->id_target);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tgl_mulai);
        }

        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tgl_selesai);
        }

        $transaksi = $query->get();
        $totalSetor = $transaksi->where('jenis_transaksi', 'SETOR')->sum('nominal');
        $totalTarik = $transaksi->where('jenis_transaksi', 'TARIK')->sum('nominal');

        return view('laporan.cetak', compact('transaksi', 'totalSetor', 'totalTarik'));
    }

    public function exportExcel(Request $request)
    {
        $query = TransaksiCelengan::with('target')->orderBy('tanggal_transaksi', 'asc');

        if ($request->filled('id_target')) {
            $query->where('id_target', $request->id_target);
        }

        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        if ($request->filled('tgl_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tgl_mulai);
        }

        if ($request->filled('tgl_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tgl_selesai);
        }

        $transaksi = $query->get();

        $filename = 'Rekap_Mutasi_Tabungan_' . date('Ymd_His') . '.xls';

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename");
        header("Pragma: no-cache");
        header("Expires: 0");

        return view('laporan.excel', compact('transaksi'));
    }
}
