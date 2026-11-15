@extends('layouts.app')

@section('title', $target->nama_target)

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-2 text-xs text-slate-500">
            <a href="{{ route('target.index') }}" class="hover:text-blue-600 transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Kembali ke Semua Target</span>
            </a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $target->nama_target }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <!-- Edit Target Button -->
            <button type="button" onclick="document.getElementById('modalEditTarget').classList.remove('hidden')"
                    class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-600 text-amber-700 hover:text-white border border-amber-200 text-xs font-semibold transition flex items-center space-x-1">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Target</span>
            </button>

            <!-- Complete Target Button -->
            @if($target->status === 'aktif')
                <form action="{{ route('target.complete', $target->id_target) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 text-xs font-semibold transition flex items-center space-x-1">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Tandai Tercapai</span>
                    </button>
                </form>
            @endif

            <!-- Delete Target Button -->
            <form action="{{ route('target.destroy', $target->id_target) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus target celengan ini? Semua data riwayat tabungan di dalamnya juga akan terhapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 text-xs font-semibold transition flex items-center space-x-1">
                    <i class="fa-solid fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Hero Detail Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
            
            <!-- Left Info -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm">
                        <i class="fa-solid {{ $target->ikon ?: 'fa-piggy-bank' }}"></i>
                    </div>
                    <div>
                        <div class="flex items-center space-x-2">
                            <h2 class="text-2xl font-bold text-slate-900">{{ $target->nama_target }}</h2>
                            @if($target->status === 'tercapai')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    🎉 TERCAPAI
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">Kategori: <strong>{{ $target->kategori }}</strong></p>
                    </div>
                </div>

                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {{ $target->catatan ?: 'Target impian tabungan pribadi.' }}
                </p>

                <!-- Nominal Breakdown -->
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <span class="text-[11px] text-slate-400 uppercase font-semibold block">Terkumpul</span>
                        <span class="text-lg font-bold text-slate-900 block mt-0.5">Rp {{ number_format($target->saldo_terkumpul, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <span class="text-[11px] text-slate-400 uppercase font-semibold block">Target Akhir</span>
                        <span class="text-lg font-bold text-slate-900 block mt-0.5">Rp {{ number_format($target->target_nominal, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-100">
                        <span class="text-[11px] text-slate-400 uppercase font-semibold block">Sisa Dibutuhkan</span>
                        <span class="text-lg font-bold text-blue-600 block mt-0.5">Rp {{ number_format($target->sisa_nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Progress Meter -->
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200/60 text-center flex flex-col items-center justify-center">
                <span class="text-4xl sm:text-5xl font-extrabold text-blue-600 tracking-tight">
                    {{ $target->persentase }}%
                </span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1">Pencapaian Target</span>
                
                @if($target->saran_harian > 0 && $target->status === 'aktif')
                    <div class="mt-4 pt-3 border-t border-slate-200 w-full text-xs text-slate-600">
                        <span class="text-[11px] text-slate-400 block">Saran Nabung Harian:</span>
                        <strong class="text-emerald-700 text-sm font-bold">Rp {{ number_format($target->saran_harian, 0, ',', '.') }} / hari</strong>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- 2 Column Section: Form Transaksi & Riwayat Mutasi -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Quick Deposit / Withdraw Form -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm h-fit">
            <h3 class="font-bold text-slate-900 text-base mb-1">Setor / Tarik Tabungan</h3>
            <p class="text-xs text-slate-400 mb-4">Transaksi langsung pada celengan ini</p>

            <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="id_target" value="{{ $target->id_target }}">

                <!-- Radio Type -->
                <div class="grid grid-cols-2 gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="jenis_transaksi" value="SETOR" class="sr-only peer" checked>
                        <div class="py-2 text-xs font-medium text-slate-600 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:font-semibold peer-checked:shadow-sm transition rounded-lg">
                            <i class="fa-solid fa-arrow-down text-emerald-600 mr-1"></i> Setor
                        </div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="jenis_transaksi" value="TARIK" class="sr-only peer">
                        <div class="py-2 text-xs font-medium text-slate-600 peer-checked:bg-white peer-checked:text-rose-700 peer-checked:font-semibold peer-checked:shadow-sm transition rounded-lg">
                            <i class="fa-solid fa-arrow-up text-rose-600 mr-1"></i> Tarik
                        </div>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" required min="1000" placeholder="Contoh: 50000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sumber Dana</label>
                    <select name="sumber_dana" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="Uang Saku">Uang Saku</option>
                        <option value="Gaji / Honor">Gaji / Honor</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Sisa Belanja">Sisa Belanja</option>
                        <option value="THR & Hadiah">THR & Hadiah</option>
                        <option value="Kebutuhan Darurat">Kebutuhan Darurat</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Tabungan mingguan"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm text-sm transition">
                    Simpan Transaksi
                </button>
            </form>
        </div>

        <!-- Right: Riwayat Mutasi Khusus Target Ini -->
        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm">
            <h3 class="font-bold text-slate-900 text-base mb-1">Buku Mutasi Celengan</h3>
            <p class="text-xs text-slate-400 mb-4">Riwayat uang masuk dan keluar pada impian ini</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase tracking-wider bg-slate-50/50">
                            <th class="py-3 px-3 font-semibold">Tanggal</th>
                            <th class="py-3 px-3 font-semibold">Jenis</th>
                            <th class="py-3 px-3 font-semibold">Nominal</th>
                            <th class="py-3 px-3 font-semibold">Sumber & Catatan</th>
                            <th class="py-3 px-3 font-semibold text-right">Saldo Sesudah</th>
                            <th class="py-3 px-3 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($target->transaksi as $tx)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-3 text-xs text-slate-500">
                                    {{ date('d M Y', strtotime($tx->tanggal_transaksi)) }}
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($tx->jenis_transaksi === 'SETOR')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                            + SETOR
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                            - TARIK
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 font-bold text-xs sm:text-sm {{ $tx->jenis_transaksi === 'SETOR' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($tx->nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-3 text-xs text-slate-600">
                                    <div class="font-medium text-slate-800">{{ $tx->sumber_dana }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $tx->keterangan ?: '-' }}</div>
                                </td>
                                <td class="py-3.5 px-3 text-right font-semibold text-xs sm:text-sm text-slate-900">
                                    Rp {{ number_format($tx->saldo_sesudah, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-3 text-center">
                                    <form action="{{ route('transaksi.destroy', $tx->id_transaksi) }}" method="POST" onsubmit="return confirm('Hapus catatan mutasi ini? Saldo celengan akan otomatis disesuaikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 hover:bg-rose-600 text-rose-500 hover:text-white transition flex items-center justify-center text-xs mx-auto" title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 text-xs">Belum ada mutasi tabungan pada target ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<!-- Modal Edit Target -->
<div id="modalEditTarget" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 sm:p-7 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-base text-slate-900">Edit Data Target Tabungan</h3>
            <button type="button" onclick="document.getElementById('modalEditTarget').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form action="{{ route('target.update', $target->id_target) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Target / Impian</label>
                <input type="text" name="nama_target" value="{{ old('nama_target', $target->nama_target) }}" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="Elektronik" {{ $target->kategori == 'Elektronik' ? 'selected' : '' }}>Elektronik & Gadget</option>
                        <option value="Kendaraan" {{ $target->kategori == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                        <option value="Liburan" {{ $target->kategori == 'Liburan' ? 'selected' : '' }}>Liburan / Travel</option>
                        <option value="Investasi" {{ $target->kategori == 'Investasi' ? 'selected' : '' }}>Investasi / Dana Darurat</option>
                        <option value="Pendidikan" {{ $target->kategori == 'Pendidikan' ? 'selected' : '' }}>Pendidikan / Kursus</option>
                        <option value="Lainnya" {{ $target->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Nominal (Rp)</label>
                    <input type="number" name="target_nominal" value="{{ old('target_nominal', $target->target_nominal) }}" required min="1000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tenggat Waktu</label>
                    <input type="date" name="tenggat_waktu" value="{{ old('tenggat_waktu', $target->tenggat_waktu) }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ikon Celengan</label>
                    <select name="ikon" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="fa-laptop-code" {{ $target->ikon == 'fa-laptop-code' ? 'selected' : '' }}>💻 Laptop / Komputer</option>
                        <option value="fa-mobile-screen" {{ $target->ikon == 'fa-mobile-screen' ? 'selected' : '' }}>📱 Smartphone</option>
                        <option value="fa-motorcycle" {{ $target->ikon == 'fa-motorcycle' ? 'selected' : '' }}>🛵 Sepeda Motor</option>
                        <option value="fa-car" {{ $target->ikon == 'fa-car' ? 'selected' : '' }}>🚗 Mobil</option>
                        <option value="fa-plane-departure" {{ $target->ikon == 'fa-plane-departure' ? 'selected' : '' }}>✈️ Liburan</option>
                        <option value="fa-shield-halved" {{ $target->ikon == 'fa-shield-halved' ? 'selected' : '' }}>🛡️ Dana Darurat</option>
                        <option value="fa-piggy-bank" {{ $target->ikon == 'fa-piggy-bank' ? 'selected' : '' }}>🐷 Celengan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">{{ old('catatan', $target->catatan) }}</textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modalEditTarget').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
