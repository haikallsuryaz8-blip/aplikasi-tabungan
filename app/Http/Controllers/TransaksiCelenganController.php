<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetTabungan;
use App\Models\TransaksiCelengan;
use Illuminate\Support\Facades\DB;

class TransaksiCelenganController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiCelengan::with('target')->orderBy('tanggal_transaksi', 'desc')->orderBy('id_transaksi', 'desc');

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

        $transaksi = $query->paginate(12)->withQueryString();
        $targets = TargetTabungan::orderBy('nama_target', 'asc')->get();

        $totalSetor = TransaksiCelengan::where('jenis_transaksi', 'SETOR')->sum('nominal');
        $totalTarik = TransaksiCelengan::where('jenis_transaksi', 'TARIK')->sum('nominal');

        return view('transaksi.index', compact('transaksi', 'targets', 'totalSetor', 'totalTarik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_target' => 'required|exists:target_tabungan,id_target',
            'jenis_transaksi' => 'required|in:SETOR,TARIK',
            'nominal' => 'required|numeric|min:1000',
            'sumber_dana' => 'required|string',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            $target = TargetTabungan::lockForUpdate()->findOrFail($request->id_target);
            $saldoSebelum = $target->saldo_terkumpul;

            if ($request->jenis_transaksi === 'TARIK') {
                if ($request->nominal > $saldoSebelum) {
                    return redirect()->back()->with('error', 'Gagal menarik tabungan! Saldo di celengan ini hanya tersisa Rp ' . number_format($saldoSebelum, 0, ',', '.') . '.')->withInput();
                }
                $saldoSesudah = $saldoSebelum - $request->nominal;
            } else {
                $saldoSesudah = $saldoSebelum + $request->nominal;
            }

            // Simpan Transaksi
            TransaksiCelengan::create([
                'id_target' => $target->id_target,
                'jenis_transaksi' => $request->jenis_transaksi,
                'nominal' => $request->nominal,
                'sumber_dana' => $request->sumber_dana,
                'tanggal_transaksi' => $request->tanggal_transaksi,
                'keterangan' => $request->keterangan ?: ($request->jenis_transaksi === 'SETOR' ? 'Menabung ke ' . $target->nama_target : 'Penarikan tabungan ' . $target->nama_target),
                'saldo_sebelum' => $saldoSebelum,
                'saldo_sesudah' => $saldoSesudah,
            ]);

            // Update Saldo Target
            $target->saldo_terkumpul = $saldoSesudah;
            if ($target->saldo_terkumpul >= $target->target_nominal) {
                $target->status = 'tercapai';
            } elseif ($target->status === 'tercapai' && $target->saldo_terkumpul < $target->target_nominal) {
                $target->status = 'aktif';
            }
            $target->save();

            $pesan = $request->jenis_transaksi === 'SETOR'
                ? '🎉 Mantap! Berhasil mengisi celengan sebesar Rp ' . number_format($request->nominal, 0, ',', '.') . ' ke ' . $target->nama_target . '!'
                : '⚠️ Penarikan tabungan sebesar Rp ' . number_format($request->nominal, 0, ',', '.') . ' berhasil dicatat.';

            return redirect()->back()->with('success', $pesan);
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $trx = TransaksiCelengan::findOrFail($id);
            $target = TargetTabungan::lockForUpdate()->findOrFail($trx->id_target);

            // Revert saldo target
            if ($trx->jenis_transaksi === 'SETOR') {
                $target->saldo_terkumpul = max(0, $target->saldo_terkumpul - $trx->nominal);
            } else {
                $target->saldo_terkumpul += $trx->nominal;
            }

            // Update status jika berubah
            if ($target->saldo_terkumpul >= $target->target_nominal) {
                $target->status = 'tercapai';
            } else {
                $target->status = 'aktif';
            }
            $target->save();

            $trx->delete();

            return redirect()->back()->with('success', 'Catatan transaksi berhasil dihapus dan saldo celengan telah disesuaikan kembali.');
        });
    }
}
