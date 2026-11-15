<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Celengan Impian') - Smart Savings Tracker</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="theme-color" content="#0f172a">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col text-slate-800 antialiased">

    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 text-white sticky top-0 z-50 border-b border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand & Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 group-hover:bg-blue-500 text-white flex items-center justify-center text-lg shadow-sm transition">
                            <i class="fa-solid fa-piggy-bank"></i>
                        </div>
                        <div>
                            <span class="text-base sm:text-lg font-bold tracking-tight text-white block">Celengan Impian</span>
                            <span class="text-[11px] text-slate-400 hidden sm:block">Smart Personal Savings Tracker</span>
                        </div>
                    </a>
                </div>

                <!-- Right Header Actions -->
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Quick Setor Button -->
                    <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.remove('hidden')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl text-xs font-semibold shadow-sm flex items-center space-x-1.5 transition active:scale-95">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span class="hidden sm:inline">Isi Celengan</span>
                    </button>

                    <!-- User Info -->
                    <div class="text-right hidden sm:block pl-2 border-l border-slate-800">
                        <div class="text-xs sm:text-sm font-semibold text-white flex items-center justify-end space-x-1.5">
                            <span>{{ Auth::user()->name ?? 'Haikal' }}</span>
                            <i class="fa-solid fa-circle-check text-emerald-400 text-xs"></i>
                        </div>
                        <div class="text-[11px] text-slate-400 font-mono">
                            {{ '@' . (Auth::user()->username ?? 'haikal') }}
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" title="Keluar"
                                class="bg-rose-500/10 hover:bg-rose-600 text-rose-300 hover:text-white border border-rose-500/30 hover:border-transparent px-3 py-2 rounded-xl text-xs font-semibold transition flex items-center space-x-1.5 shadow-sm">
                            <i class="fa-solid fa-power-off text-xs"></i>
                            <span class="hidden md:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex-1 flex max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 gap-6">
        <!-- Sidebar Navigation (Desktop) -->
        <aside class="w-64 flex-shrink-0 hidden md:block">
            <nav class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-3 space-y-1 sticky top-24">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-600' }}"></i>
                    <span>Dashboard Utama</span>
                </a>

                <div class="pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">
                    Manajemen Tabungan
                </div>
                <a href="{{ route('target.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm transition-all {{ request()->routeIs('target.*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-bullseye w-5 text-center {{ request()->routeIs('target.*') ? 'text-white' : 'text-blue-600' }}"></i>
                    <span>Target Impian</span>
                </a>
                <a href="{{ route('transaksi.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm transition-all {{ request()->routeIs('transaksi.*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center {{ request()->routeIs('transaksi.*') ? 'text-white' : 'text-blue-600' }}"></i>
                    <span>Buku Mutasi Tabungan</span>
                </a>

                <div class="pt-3 pb-1 text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3">
                    Rekapitulasi
                </div>
                <a href="{{ route('laporan.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-medium text-xs sm:text-sm transition-all {{ request()->routeIs('laporan.*') ? 'bg-blue-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center {{ request()->routeIs('laporan.*') ? 'text-white' : 'text-blue-600' }}"></i>
                    <span>Laporan & Export</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden pb-20 md:pb-6">
            <!-- Flash Notifications -->
            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center space-x-3 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base"></i>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl shadow-sm">
                    <div class="flex items-center space-x-2 font-bold text-xs sm:text-sm mb-1.5">
                        <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                        <span>Terdapat kesalahan input:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700 pl-4 font-medium">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bottom Navigation Bar (Mobile) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-50 px-2 py-1.5 shadow-lg">
        <div class="flex items-center justify-around">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('dashboard') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-chart-pie text-base mb-0.5"></i>
                <span class="text-[10px]">Dashboard</span>
            </a>
            <a href="{{ route('target.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('target.*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-bullseye text-base mb-0.5"></i>
                <span class="text-[10px]">Target</span>
            </a>
            <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.remove('hidden')" class="flex flex-col items-center py-1 px-3 text-blue-600 font-bold">
                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center -mt-3 shadow-md">
                    <i class="fa-solid fa-plus text-xs"></i>
                </div>
                <span class="text-[10px] mt-0.5">Nabung</span>
            </button>
            <a href="{{ route('transaksi.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('transaksi.*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-clock-rotate-left text-base mb-0.5"></i>
                <span class="text-[10px]">Mutasi</span>
            </a>
            <a href="{{ route('laporan.index') }}" class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('laporan.*') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800' }}">
                <i class="fa-solid fa-file-invoice-dollar text-base mb-0.5"></i>
                <span class="text-[10px]">Laporan</span>
            </a>
        </div>
    </nav>

    <!-- Global Modal Setor / Tarik Celengan -->
    @php
        $globalTargets = \App\Models\TargetTabungan::where('status', 'aktif')->orderBy('nama_target', 'asc')->get();
    @endphp
    <div id="modalSetorGlobal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl shadow-xl w-full max-w-md p-6 sm:p-7 relative">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2.5">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-base">
                        <i class="fa-solid fa-piggy-bank"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-slate-900">Transaksi Celengan</h3>
                        <p class="text-xs text-slate-400">Isi tabungan atau lakukan penarikan dana</p>
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.add('hidden')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>

            <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Jenis Transaksi Radio -->
                <div class="grid grid-cols-2 gap-1.5 bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="jenis_transaksi" value="SETOR" class="sr-only peer" checked>
                        <div class="py-2 px-2 rounded-lg text-xs font-medium text-slate-600 peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:font-semibold peer-checked:shadow-sm transition flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-arrow-down text-emerald-600 text-xs"></i>
                            <span>Isi Celengan (Setor)</span>
                        </div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="jenis_transaksi" value="TARIK" class="sr-only peer">
                        <div class="py-2 px-2 rounded-lg text-xs font-medium text-slate-600 peer-checked:bg-white peer-checked:text-rose-700 peer-checked:font-semibold peer-checked:shadow-sm transition flex items-center justify-center space-x-1.5">
                            <i class="fa-solid fa-arrow-up text-rose-600 text-xs"></i>
                            <span>Tarik Tabungan</span>
                        </div>
                    </label>
                </div>

                <!-- Target Tabungan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Target Impian</label>
                    <select name="id_target" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none bg-white font-medium">
                        @foreach($globalTargets as $tgt)
                            <option value="{{ $tgt->id_target }}">
                                {{ $tgt->nama_target }} (Saldo: Rp {{ number_format($tgt->saldo_terkumpul, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" required min="1000" placeholder="Contoh: 50000"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm font-bold text-slate-900 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>

                <!-- Sumber Dana & Tanggal -->
                <div class="grid grid-cols-2 gap-3">
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
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Keterangan (Opsional)</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Sisa uang jajan hari Jumat"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('modalSetorGlobal').classList.add('hidden')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
