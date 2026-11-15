<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TargetTabungan;
use Illuminate\Support\Facades\Auth;

class TargetTabunganController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = TargetTabungan::with('transaksi')->orderBy('status', 'asc')->orderBy('tenggat_waktu', 'asc');

        if ($status && in_array($status, ['aktif', 'tercapai'])) {
            $query->where('status', $status);
        }

        $targets = $query->paginate(9);
        return view('target.index', compact('targets', 'status'));
    }

    public function show($id)
    {
        $target = TargetTabungan::with('transaksi')->findOrFail($id);
        return view('target.show', compact('target'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_target' => 'required|string|max:255',
            'kategori' => 'required|string',
            'target_nominal' => 'required|numeric|min:1000',
            'tenggat_waktu' => 'nullable|date',
            'ikon' => 'nullable|string',
            'warna' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        TargetTabungan::create([
            'user_id' => Auth::id() ?? 1,
            'nama_target' => $request->nama_target,
            'kategori' => $request->kategori,
            'target_nominal' => $request->target_nominal,
            'saldo_terkumpul' => 0,
            'tenggat_waktu' => $request->tenggat_waktu,
            'ikon' => $request->ikon ?: 'fa-piggy-bank',
            'warna' => $request->warna ?: 'blue',
            'status' => 'aktif',
            'catatan' => $request->catatan,
        ]);

        return redirect()->back()->with('success', 'Target impian baru berhasil dibuat! Ayo mulai menabung! 🚀');
    }

    public function update(Request $request, $id)
    {
        $target = TargetTabungan::findOrFail($id);

        $request->validate([
            'nama_target' => 'required|string|max:255',
            'kategori' => 'required|string',
            'target_nominal' => 'required|numeric|min:1000',
            'tenggat_waktu' => 'nullable|date',
            'ikon' => 'nullable|string',
            'warna' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $target->update($request->only([
            'nama_target',
            'kategori',
            'target_nominal',
            'tenggat_waktu',
            'ikon',
            'warna',
            'catatan',
        ]));

        // Cek jika saldo sudah mencapai target, ubah status jadi tercapai
        if ($target->saldo_terkumpul >= $target->target_nominal) {
            $target->status = 'tercapai';
            $target->save();
        }

        return redirect()->back()->with('success', 'Data target tabungan berhasil diperbarui! ✨');
    }

    public function destroy($id)
    {
        $target = TargetTabungan::findOrFail($id);
        $target->delete();

        return redirect()->route('target.index')->with('success', 'Target tabungan berhasil dihapus.');
    }

    public function markCompleted($id)
    {
        $target = TargetTabungan::findOrFail($id);
        $target->status = 'tercapai';
        $target->save();

        return redirect()->back()->with('success', '🎉 Selamat! Target impian berhasil ditandai sebagai TERCAPAI!');
    }
}
