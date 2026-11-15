<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Celengan Impian</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Font Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 text-slate-800 antialiased">

    <div class="w-full max-w-md relative z-10">
        <!-- Main Card -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/70 border border-slate-200/80 p-7 sm:p-9">
            
            <!-- Logo & Brand Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-500/25 mb-3.5">
                    <i class="fa-solid fa-piggy-bank text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Celengan Impian</h1>
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mt-1">
                    Smart Personal Savings Tracker
                </p>
                <div class="inline-flex items-center space-x-1.5 mt-2.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-[11px] font-medium text-slate-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Wujudkan Semua Impian Finansialmu</span>
                </div>
            </div>

            <!-- Flash Notifications -->
            @if(session('error'))
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-sm flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Username / Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Username atau Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" name="login" value="{{ old('login', 'haikal') }}" required autofocus
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition shadow-sm font-medium"
                               placeholder="Ketik username atau email">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-semibold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" id="passwordInput" value="haikal123" required
                               class="w-full pl-10 pr-11 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition shadow-sm font-medium"
                               placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" aria-label="Toggle password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition focus:outline-none">
                            <i id="eyeIcon" class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                    <span class="text-[11px] text-blue-600 font-semibold bg-blue-50 px-2.5 py-0.5 rounded-md">Akun: haikal / haikal123</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-md shadow-blue-600/20 transition-all duration-150 active:scale-[0.99] text-sm flex items-center justify-center space-x-2">
                    <span>Buka Celengan Saya</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-[11px] text-slate-400">
                <span>Celengan Impian &copy; 2026 • Haikal Suryadarmansyah</span>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
