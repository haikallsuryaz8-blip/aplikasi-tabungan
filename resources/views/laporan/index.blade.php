@extends('layouts.app')

@section('title', 'Rekapitulasi Laporan Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Rekapitulasi Laporan</h1>
            <p class="text-xs text-slate-500">Laporan mutasi tabungan dan progres keuangan pribadi</p>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Cetak PDF Button -->
            <a href="{{ route('laporan.cetak', request()->all()) }}" target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-xl text-xs sm:text-sm shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Cetak PDF</span>
            </a>
            <!-- Export Excel Button -->
            <a href="{{ route('laporan.excel', request()->all()) }}" 
               class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-xl text-xs sm:text-sm shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-file-excel"></i>
                <span>Ekspor Excel</span>
            </a>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Setoran</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-arrow-down-long"></i>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-emerald-600 font-mono">
                Rp {{ number_format($totalSetor, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Total dana masuk dari pemasukan/uang saku
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Penarikan</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-arrow-up-long"></i>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-rose-600 font-mono">
                Rp {{ number_format($totalTarik, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Total dana yang ditarik untuk kebutuhan/target
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Saldo Tabungan Bersih</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                    <i class="fa-solid fa-vault"></i>
                </span>
            </div>
            <div class="text-xl sm:text-2xl font-bold text-blue-600 font-mono">
                Rp {{ number_format($netSavings, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-slate-400 mt-1">
                Akumulasi saldo celengan aktif saat ini
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-5">
        <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-slate-500 mb-1">Target Impian</label>
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
                    <span>Terapkan</span>
                </button>
                @if(request()->anyFilled(['id_target', 'jenis_transaksi', 'tgl_mulai', 'tgl_selesai']))
                    <a href="{{ route('laporan.index') }}" title="Reset" 
                       class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-xl text-xs transition flex items-center justify-center h-[38px]">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700">Daftar Transaksi Terfilter ({{ count($transaksi) }} Baris)</span>
            <span class="text-[11px] text-slate-400">Periode: {{ request('tgl_mulai') ? date('d/m/Y', strtotime(request('tgl_mulai'))) : 'Awal' }} s/d {{ request('tgl_selesai') ? date('d/m/Y', strtotime(request('tgl_selesai'))) : 'Sekarang' }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-bold">
                    <tr>
                        <th class="py-3.5 px-4 w-12 text-center">No</th>
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Target Celengan</th>
                        <th class="py-3.5 px-4 text-center">Jenis</th>
                        <th class="py-3.5 px-4">Sumber Dana</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4 text-right">Nominal</th>
                        <th class="py-3.5 px-4 text-right">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-normal">
                    @forelse($transaksi as $index => $trx)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 text-center text-slate-400 font-mono">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d/m/Y') }}</span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap font-medium text-slate-800">
                                {{ $trx->target->nama_target ?? '-' }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                @if($trx->jenis_transaksi === 'SETOR')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        SETOR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        TARIK
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-[11px]">
                                    {{ $trx->sumber_dana }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 max-w-xs truncate">
                                {{ $trx->keterangan ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap font-mono font-bold {{ $trx->jenis_transaksi === 'SETOR' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->jenis_transaksi === 'SETOR' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap font-mono font-semibold text-slate-800">
                                Rp {{ number_format($trx->saldo_sesudah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400">
                                Belum ada data transaksi yang sesuai filter
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($transaksi) > 0)
                    <tfoot class="bg-slate-50 font-bold border-t border-slate-200 text-slate-800">
                        <tr>
                            <td colspan="6" class="py-3.5 px-4 text-right uppercase text-[11px] tracking-wider text-slate-600">Total Akumulasi:</td>
                            <td class="py-3.5 px-4 text-right font-mono text-emerald-600">
                                Rp {{ number_format($totalSetor - $totalTarik, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4"></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
