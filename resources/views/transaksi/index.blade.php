@extends('layouts.app')

@section('title', 'Buku Mutasi Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Buku Mutasi Tabungan</h1>
            <p class="text-xs text-slate-500">Catatan kronologis seluruh setoran dan penarikan celengan impian Anda</p>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.remove('hidden')"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl text-xs sm:text-sm shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Catat Transaksi</span>
            </button>
            <a href="{{ route('laporan.index') }}" 
               class="bg-white hover:bg-slate-50 text-slate-700 font-semibold px-4 py-2 rounded-xl text-xs sm:text-sm border border-slate-200 shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Laporan & Ekspor</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-arrow-down-long"></i>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Total Setoran</span>
                <span class="text-lg font-bold text-slate-800">Rp {{ number_format($totalSetor, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-arrow-up-long"></i>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Total Penarikan</span>
                <span class="text-lg font-bold text-slate-800">Rp {{ number_format($totalTarik, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-3.5">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 block uppercase tracking-wider">Saldo Bersih Terkumpul</span>
                <span class="text-lg font-bold text-blue-600">Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5">
        <form action="{{ route('transaksi.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Target Celengan</label>
                <select name="id_target" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 bg-slate-50 text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Target</option>
                    @foreach($targets as $t)
                        <option value="{{ $t->id_target }}" {{ request('id_target') == $t->id_target ? 'selected' : '' }}>
                            {{ $t->nama_target }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Jenis Transaksi</label>
                <select name="jenis_transaksi" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 bg-slate-50 text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Jenis</option>
                    <option value="SETOR" {{ request('jenis_transaksi') == 'SETOR' ? 'selected' : '' }}>Setoran (Masuk)</option>
                    <option value="TARIK" {{ request('jenis_transaksi') == 'TARIK' ? 'selected' : '' }}>Penarikan (Keluar)</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Mulai Tanggal</label>
                <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" 
                       class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 bg-slate-50 text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" 
                       class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 bg-slate-50 text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-semibold px-4 py-2 rounded-xl text-xs shadow-sm transition flex items-center justify-center space-x-1.5 h-[38px]">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filter</span>
                </button>
                @if(request()->anyFilled(['id_target', 'jenis_transaksi', 'tgl_mulai', 'tgl_selesai']))
                    <a href="{{ route('transaksi.index') }}" title="Reset Filter" 
                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs transition flex items-center justify-center h-[38px]">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Mutasi -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                    <tr>
                        <th class="py-3.5 px-4">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4">Target Impian</th>
                        <th class="py-3.5 px-4 text-center">Jenis</th>
                        <th class="py-3.5 px-4">Sumber Dana</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4 text-right">Nominal</th>
                        <th class="py-3.5 px-4 text-right">Saldo Celengan</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-normal">
                    @forelse($transaksi as $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-800 block">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($trx->created_at)->format('H:i:s') }} WIB</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <a href="{{ route('target.show', $trx->id_target) }}" class="font-semibold text-slate-800 hover:text-blue-600 flex items-center space-x-2 group">
                                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs group-hover:bg-blue-600 group-hover:text-white transition">
                                        <i class="fa-solid fa-{{ $trx->target->ikon ?? 'bullseye' }}"></i>
                                    </span>
                                    <span>{{ $trx->target->nama_target ?? '-' }}</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @if($trx->jenis_transaksi === 'SETOR')
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-arrow-down text-[9px]"></i>
                                        <span>SETOR</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-arrow-up text-[9px]"></i>
                                        <span>TARIK</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $trx->sumber_dana }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate" title="{{ $trx->keterangan }}">
                                {{ $trx->keterangan ?: '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                @if($trx->jenis_transaksi === 'SETOR')
                                    <span class="font-bold text-emerald-600 text-sm font-mono">+ Rp {{ number_format($trx->nominal, 0, ',', '.') }}</span>
                                @else
                                    <span class="font-bold text-rose-600 text-sm font-mono">- Rp {{ number_format($trx->nominal, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="font-semibold text-slate-800 font-mono">Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">Sebelum: Rp {{ number_format($trx->saldo_sebelum, 0, ',', '.') }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('transaksi.destroy', $trx->id_transaksi) }}" method="POST" onsubmit="return confirm('Hapus catatan mutasi ini? Saldo target akan otomatis disesuaikan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-rose-600 text-slate-400 hover:text-white transition flex items-center justify-center text-xs mx-auto" title="Hapus Mutasi">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                                <span class="text-xs font-medium">Belum ada riwayat mutasi yang sesuai filter</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksi->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $transaksi->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
