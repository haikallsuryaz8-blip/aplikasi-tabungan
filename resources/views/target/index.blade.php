@extends('layouts.app')

@section('title', 'Target Impian & Celengan')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Target Impian & Celengan</h1>
            <p class="text-xs text-slate-500">Kelola impian masa depanmu dan pantau perkembangannya</p>
        </div>
        <button type="button" onclick="document.getElementById('modalTambahTarget').classList.remove('hidden')"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-sm transition flex items-center space-x-2 self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Buat Target Baru</span>
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 text-xs font-semibold">
        <a href="{{ route('target.index') }}" class="px-3.5 py-1.5 rounded-lg transition {{ !$status ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua ({{ \App\Models\TargetTabungan::count() }})
        </a>
        <a href="{{ route('target.index', ['status' => 'aktif']) }}" class="px-3.5 py-1.5 rounded-lg transition {{ $status === 'aktif' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sedang Aktif ({{ \App\Models\TargetTabungan::where('status', 'aktif')->count() }})
        </a>
        <a href="{{ route('target.index', ['status' => 'tercapai']) }}" class="px-3.5 py-1.5 rounded-lg transition {{ $status === 'tercapai' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Sudah Tercapai 🎉 ({{ \App\Models\TargetTabungan::where('status', 'tercapai')->count() }})
        </a>
    </div>

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($targets as $tgt)
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <!-- Top Badge & Action Icons -->
                    <div class="flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                            <i class="fa-solid {{ $tgt->ikon ?: 'fa-piggy-bank' }}"></i>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            @if($tgt->status === 'tercapai')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    🎉 TERCAPAI
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    {{ $tgt->kategori }}
                                </span>
                            @endif

                            <!-- Quick Edit Button -->
                            <button type="button" onclick="openEditModal({{ json_encode($tgt) }})" title="Edit Target"
                                    class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-amber-500 hover:text-white text-slate-500 transition flex items-center justify-center text-xs">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            <!-- Quick Delete Button -->
                            <form action="{{ route('target.destroy', $tgt->id_target) }}" method="POST" onsubmit="return confirm('Hapus target {{ $tgt->nama_target }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Target"
                                        class="w-7 h-7 rounded-lg bg-slate-100 hover:bg-rose-500 hover:text-white text-slate-500 transition flex items-center justify-center text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Target Title -->
                    <div class="mt-4">
                        <h3 class="font-bold text-slate-900 text-base line-clamp-1" title="{{ $tgt->nama_target }}">
                            {{ $tgt->nama_target }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">
                            {{ $tgt->catatan ?: 'Target impian finansial' }}
                        </p>
                    </div>

                    <!-- Nominal Progress -->
                    <div class="mt-4 pt-3 border-t border-slate-100">
                        <div class="flex items-baseline justify-between">
                            <span class="text-lg font-extrabold text-slate-900">
                                Rp {{ number_format($tgt->saldo_terkumpul, 0, ',', '.') }}
                            </span>
                            <span class="text-xs text-slate-400">
                                dari Rp {{ number_format($tgt->target_nominal, 0, ',', '.') }}
                            </span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="mt-2">
                            <div class="flex justify-between text-[11px] mb-1 font-semibold">
                                <span class="text-slate-500">Progres</span>
                                <span class="text-blue-600">{{ $tgt->persentase }}%</span>
                            </div>
                            <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-blue-600 h-full rounded-full transition-all" style="width: {{ max($tgt->persentase, 4) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action Buttons -->
                <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                    <span class="text-slate-400 text-[11px]">
                        @if($tgt->sisa_hari !== null)
                            @if($tgt->sisa_hari > 0)
                                Sisa {{ $tgt->sisa_hari }} hari lagi
                            @elseif($tgt->sisa_hari == 0)
                                Hari ini batasnya!
                            @else
                                Lewat tempo
                            @endif
                        @else
                            Tanpa batas waktu
                        @endif
                    </span>
                    <a href="{{ route('target.show', $tgt->id_target) }}" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white rounded-xl text-xs font-semibold transition flex items-center space-x-1">
                        <span>Buka Celengan</span>
                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white rounded-3xl p-10 border border-slate-200 text-center">
                <i class="fa-solid fa-bullseye text-4xl text-slate-300 mb-2"></i>
                <h3 class="font-bold text-slate-700">Tidak Ada Target yang Ditemukan</h3>
                <p class="text-xs text-slate-400 mt-1 mb-4">Mulai buat impian pertamamu sekarang!</p>
                <button type="button" onclick="document.getElementById('modalTambahTarget').classList.remove('hidden')"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm">
                    Buat Target Baru
                </button>
            </div>
        @endforelse
    </div>

    <div class="pt-4">
        {{ $targets->links() }}
    </div>

</div>

<!-- Modal Tambah Target -->
<div id="modalTambahTarget" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 sm:p-7 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-base text-slate-900">Buat Target Impian Baru</h3>
            <button type="button" onclick="document.getElementById('modalTambahTarget').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form action="{{ route('target.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Target / Impian</label>
                <input type="text" name="nama_target" required placeholder="Contoh: Laptop Asus ROG / Motor NMAX / Liburan"
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
                        <option value="fa-piggy-bank">🐷 Celengan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                <textarea name="catatan" rows="2" placeholder="Catatan motivasi..."
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modalTambahTarget').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Target
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Target Dinamis -->
<div id="modalEditTargetIndex" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg p-6 sm:p-7 relative max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-base text-slate-900">Edit Data Target Tabungan</h3>
            <button type="button" onclick="document.getElementById('modalEditTargetIndex').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        <form id="formEditTargetIndex" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Target / Impian</label>
                <input type="text" id="editNamaTarget" name="nama_target" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                    <select id="editKategori" name="kategori" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
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
                    <input type="number" id="editTargetNominal" name="target_nominal" required min="1000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tenggat Waktu</label>
                    <input type="date" id="editTenggatWaktu" name="tenggat_waktu"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Ikon Celengan</label>
                    <select id="editIkon" name="ikon" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white">
                        <option value="fa-laptop-code">💻 Laptop / Komputer</option>
                        <option value="fa-mobile-screen">📱 Smartphone</option>
                        <option value="fa-motorcycle">🛵 Sepeda Motor</option>
                        <option value="fa-car">🚗 Mobil</option>
                        <option value="fa-plane-departure">✈️ Liburan</option>
                        <option value="fa-shield-halved">🛡️ Dana Darurat</option>
                        <option value="fa-piggy-bank">🐷 Celengan</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                <textarea id="editCatatan" name="catatan" rows="2" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modalEditTargetIndex').classList.add('hidden')"
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(target) {
    const form = document.getElementById('formEditTargetIndex');
    form.action = '/target/' + target.id_target;
    document.getElementById('editNamaTarget').value = target.nama_target || '';
    document.getElementById('editKategori').value = target.kategori || 'Lainnya';
    document.getElementById('editTargetNominal').value = target.target_nominal || '';
    document.getElementById('editTenggatWaktu').value = target.tenggat_waktu ? target.tenggat_waktu.substring(0, 10) : '';
    document.getElementById('editIkon').value = target.ikon || 'fa-piggy-bank';
    document.getElementById('editCatatan').value = target.catatan || '';
    document.getElementById('modalEditTargetIndex').classList.remove('hidden');
}
</script>
@endsection
