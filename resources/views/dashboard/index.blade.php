@extends('layouts.app')

@section('title', 'Dashboard Tabungan')

@section('content')
<div class="space-y-6">

    <!-- Hero Card: Total Kekayaan & Progress Global -->
    <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2 max-w-xl">
                <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-xs font-semibold text-blue-200 border border-white/15">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Total Saldo Tabungan Aktif</span>
                    <span class="text-white/40">•</span>
                    <span class="text-emerald-300 font-bold">{{ $totalTercapai }} Impian Tercapai</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                    Total target seluruh impian: <strong>Rp {{ number_format($totalTargetNominal, 0, ',', '.') }}</strong> (Tercapai {{ $persentaseTotal }}%).
                </p>
                <!-- Global Progress Bar -->
                <div class="pt-2">
                    <div class="w-full bg-white/10 h-2.5 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-emerald-400 h-full rounded-full transition-all duration-500" style="width: {{ max($persentaseTotal, 4) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.remove('hidden')"
                        class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-3 rounded-2xl text-xs sm:text-sm shadow-md transition flex items-center space-x-2 active:scale-95">
                    <i class="fa-solid fa-plus-circle text-sm"></i>
                    <span>Isi Celengan</span>
                </button>
                <button type="button" onclick="document.getElementById('modalTambahTarget').classList.remove('hidden')"
                        class="bg-white/10 hover:bg-white/20 text-white font-semibold px-4 py-3 rounded-2xl text-xs sm:text-sm backdrop-blur-md transition flex items-center space-x-2 border border-white/15">
                    <i class="fa-solid fa-bullseye text-sm"></i>
                    <span>Target Baru</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Smart Daily Savings Advice Widget -->
    @if($priorityGoal && $priorityGoal->saran_harian > 0)
        <div class="bg-blue-50/80 border border-blue-200/90 rounded-2xl p-4 sm:p-5 flex items-center justify-between gap-4 shadow-sm">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg flex-shrink-0 shadow-sm">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-blue-900 uppercase tracking-wider">Tips Nabung Cerdas Hari Ini</h4>
                    <p class="text-xs sm:text-sm text-slate-700 mt-0.5">
                        Untuk mencapai <strong class="text-blue-700">{{ $priorityGoal->nama_target }}</strong> tepat waktu (sisa {{ $priorityGoal->sisa_hari }} hari), sisihkan sekitar <strong class="text-emerald-700 font-bold">Rp {{ number_format($priorityGoal->saran_harian, 0, ',', '.') }} / hari</strong>.
                    </p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.remove('hidden')"
                    class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm flex-shrink-0 transition">
                <span>Nabung Sekarang</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>
    @endif

    <!-- Section: Target Impian Aktif (Cards Grid) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Celengan & Target Impian</h3>
                <p class="text-xs text-slate-400">Pantau progres celengan yang sedang kamu kumpulkan</p>
            </div>
            <a href="{{ route('target.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($targets as $tgt)
                <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <!-- Header Card -->
                        <div class="flex items-start justify-between">
                            <div class="w-11 h-11 rounded-xl bg-{{ $tgt->warna ?? 'blue' }}-50 text-{{ $tgt->warna ?? 'blue' }}-600 flex items-center justify-center text-lg">
                                <i class="fa-solid {{ $tgt->ikon ?: 'fa-piggy-bank' }}"></i>
                            </div>
                            @if($tgt->status === 'tercapai')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 flex items-center space-x-1">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>TERCAPAI</span>
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    {{ $tgt->kategori }}
                                </span>
                            @endif
                        </div>

                        <!-- Title & Amounts -->
                        <div class="mt-3">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-1" title="{{ $tgt->nama_target }}">
                                {{ $tgt->nama_target }}
                            </h4>
                            <div class="mt-1 flex items-baseline justify-between">
                                <span class="text-base font-extrabold text-slate-900">
                                    Rp {{ number_format($tgt->saldo_terkumpul, 0, ',', '.') }}
                                </span>
                                <span class="text-[11px] text-slate-400 font-medium">
                                    / {{ number_format($tgt->target_nominal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-3">
                            <div class="flex justify-between text-[11px] mb-1 font-semibold">
                                <span class="text-slate-500">Terkumpul</span>
                                <span class="text-blue-600">{{ $tgt->persentase }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ max($tgt->persentase, 5) }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Info & Action -->
                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 text-[11px]">
                            @if($tgt->sisa_hari !== null)
                                @if($tgt->sisa_hari > 0)
                                    Sisa {{ $tgt->sisa_hari }} hari
                                @elseif($tgt->sisa_hari == 0)
                                    Hari ini!
                                @else
                                    Lewat tempo
                                @endif
                            @else
                                Tanpa tenggat
                            @endif
                        </span>
                        <a href="{{ route('target.show', $tgt->id_target) }}" class="font-semibold text-blue-600 hover:text-blue-800 text-xs">
                            Detail & Mutasi &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 bg-white rounded-2xl p-8 border border-slate-200 text-center">
                    <i class="fa-solid fa-piggy-bank text-4xl text-slate-300 mb-2"></i>
                    <h4 class="font-bold text-slate-700">Belum Ada Target Impian</h4>
                    <p class="text-xs text-slate-400 mt-1 mb-4">Mulai buat celengan pertamamu sekarang!</p>
                    <button type="button" onclick="document.getElementById('modalTambahTarget').classList.remove('hidden')"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm">
                        Buat Target Sekarang
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tren Pertumbuhan Tabungan 6 Bulan (Line Chart) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Tren Arus Kas Celengan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Perbandingan setoran vs penarikan 6 bulan terakhir</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                    6 Bulan Terakhir
                </span>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="chartTrenBulanan"></canvas>
            </div>
        </div>

        <!-- Donut Chart: Alokasi per Kategori -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Alokasi Dana Impian</h3>
                <p class="text-xs text-slate-400 mt-0.5">Sebaran dana tabungan berdasarkan kategori</p>

                <div class="h-44 flex items-center justify-center my-3">
                    <canvas id="chartKategori"></canvas>
                </div>
            </div>

            <!-- Legend List -->
            <div class="space-y-2 pt-4 border-t border-slate-100 text-xs">
                @foreach($kategoriStats as $kat => $total)
                    <div class="flex justify-between font-medium">
                        <span class="text-slate-600">{{ $kat }}</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-slate-900 text-base">Aktivitas Celengan Terakhir</h3>
                <p class="text-xs text-slate-400 mt-0.5">Catatan setoran dan penarikan yang baru saja terjadi</p>
            </div>
            <a href="{{ route('transaksi.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 flex items-center space-x-1.5 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition">
                <span>Semua Mutasi</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b border-slate-200 text-xs text-slate-500 uppercase tracking-wider bg-slate-50/50">
                        <th class="py-3 px-3.5 font-semibold">Tanggal</th>
                        <th class="py-3 px-3.5 font-semibold">Target Impian</th>
                        <th class="py-3 px-3.5 font-semibold">Jenis</th>
                        <th class="py-3 px-3.5 font-semibold">Nominal</th>
                        <th class="py-3 px-3.5 font-semibold">Sumber Dana / Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transaksiTerbaru as $trx)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-3.5 text-xs text-slate-500">
                                {{ date('d M Y', strtotime($trx->tanggal_transaksi)) }}
                            </td>
                            <td class="py-3.5 px-3.5">
                                <span class="font-bold text-slate-800 text-xs sm:text-sm">{{ $trx->target->nama_target ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-3.5">
                                @if($trx->jenis_transaksi === 'SETOR')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> SETOR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> TARIK
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3.5 font-bold text-xs sm:text-sm {{ $trx->jenis_transaksi === 'SETOR' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trx->jenis_transaksi === 'SETOR' ? '+' : '-' }} Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-3.5 text-xs text-slate-600">
                                <div class="font-medium text-slate-700">{{ $trx->sumber_dana }}</div>
                                <div class="text-[11px] text-slate-400">{{ $trx->keterangan ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400 text-xs">Belum ada transaksi di celenganmu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Buat Target Impian Baru -->
<div id="modalTambahTarget" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 sm:p-7 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <div>
                    <h3 class="font-bold text-base text-slate-900">Buat Target Impian Baru</h3>
                    <p class="text-xs text-slate-400">Rencanakan target yang ingin kamu raih</p>
                </div>
            </div>
            <button type="button" onclick="document.getElementById('modalTambahTarget').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('target.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Target / Impian</label>
                <input type="text" name="nama_target" required placeholder="Contoh: Laptop Asus ROG / NMAX / Liburan"
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="Elektronik">Elektronik & Gadget</option>
                        <option value="Kendaraan">Kendaraan</option>
                        <option value="Liburan">Liburan / Travel</option>
                        <option value="Investasi">Investasi / Dana Darurat</option>
                        <option value="Pendidikan">Pendidikan / Kursus</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Nominal (Rp)</label>
                    <input type="number" name="target_nominal" required min="1000" placeholder="Contoh: 15000000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tenggat Waktu</label>
                    <input type="date" name="tenggat_waktu"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ikon Celengan</label>
                    <select name="ikon" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="fa-laptop-code">💻 Laptop / Komputer</option>
                        <option value="fa-mobile-screen">📱 Smartphone</option>
                        <option value="fa-motorcycle">🛵 Sepeda Motor</option>
                        <option value="fa-car">🚗 Mobil</option>
                        <option value="fa-plane-departure">✈️ Liburan</option>
                        <option value="fa-shield-halved">🛡️ Dana Darurat</option>
                        <option value="fa-house">🏠 Rumah / Kos</option>
                        <option value="fa-piggy-bank">🐷 Celengan</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Motivasi</label>
                <textarea name="catatan" rows="2" placeholder="Tuliskan motivasi kenapa kamu harus mencapai impian ini..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modalTambahTarget').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Target Impian
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chart Scripts -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Line Chart Tren
        const ctxTren = document.getElementById('chartTrenBulanan').getContext('2d');
        new Chart(ctxTren, {
            type: 'bar',
            data: {
                labels: {!! json_encode($bulanLabels) !!},
                datasets: [
                    {
                        label: 'Setor (Nabung)',
                        data: {!! json_encode($trenSetor) !!},
                        backgroundColor: '#2563eb',
                        borderRadius: 6,
                    },
                    {
                        label: 'Tarik',
                        data: {!! json_encode($trenTarik) !!},
                        backgroundColor: '#f43f5e',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(v) { return 'Rp ' + (v/1000) + 'k'; },
                            font: { size: 10, family: 'Plus Jakarta Sans' }
                        }
                    },
                    x: {
                        ticks: { font: { size: 10, family: 'Plus Jakarta Sans' } }
                    }
                }
            }
        });

        // Donut Chart Kategori
        const ctxKat = document.getElementById('chartKategori').getContext('2d');
        const labelsKat = {!! json_encode(array_keys($kategoriStats)) !!};
        const dataKat = {!! json_encode(array_values($kategoriStats)) !!};

        new Chart(ctxKat, {
            type: 'doughnut',
            data: {
                labels: labelsKat.length ? labelsKat : ['Tabungan'],
                datasets: [{
                    data: dataKat.length ? dataKat : [1],
                    backgroundColor: ['#2563eb', '#7c3aed', '#059669', '#d97706', '#0284c7'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    });
</script>
@endsection
