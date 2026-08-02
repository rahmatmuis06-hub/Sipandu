<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk – SIPANDU BPMP Gorontalo</title>
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script src="https://cdn.jsdelivr.net/npm/lucide@0.263.0/dist/umd/lucide.min.js"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { jakarta: ['Plus Jakarta Sans', 'sans-serif'] },
          colors: {
            navy: { 50:'#eef3ff',100:'#d9e4ff',200:'#bccfff',300:'#8eaeff',400:'#5a82ff',500:'#3355ff',600:'#1a2f9b',700:'#0f1f6e',800:'#0a1550',900:'#060d33',950:'#030718' },
          }
        }
      }
    }
  </script>
  <style>
    html, body { height: 100%; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif; }

    @keyframes fadeUp       { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    @keyframes slideInRight { from { opacity:0; transform:translateX(40px); } to { opacity:1; transform:translateX(0); } }
    @keyframes floatBob     { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-12px); } }

    .anim-fade-up  { animation: fadeUp 0.7s ease forwards; }
    .anim-slide-in { animation: slideInRight 0.6s ease forwards; }
    .delay-1 { animation-delay:0.1s; opacity:0; }
    .delay-2 { animation-delay:0.2s; opacity:0; }
    .delay-3 { animation-delay:0.3s; opacity:0; }

    .btn-primary { background: linear-gradient(135deg, #1a2f9b 0%, #3355ff 100%); transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,47,155,0.35); }
    .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }
    .auth-bg  { background: linear-gradient(135deg, #060d33 0%, #0f1f6e 50%, #1a2f9b 100%); }
    .auth-input { transition: all 0.2s ease; }
    .auth-input:focus { border-color: #3355ff; box-shadow: 0 0 0 3px rgba(51,85,255,0.12); outline: none; }
    .float-anim { animation: floatBob 4s ease-in-out infinite; }
    .custom-check { appearance: none; width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 5px; cursor: pointer; transition: all 0.2s; flex-shrink: 0; position: relative; }
    .custom-check:checked { background: #3355ff; border-color: #3355ff; }
    .custom-check:checked::after { content: ''; position: absolute; left: 3px; top: 0px; width: 5px; height: 10px; border: 2px solid white; border-top: none; border-left: none; transform: rotate(45deg); }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #bccfff; border-radius: 3px; }
  </style>
</head>
<body class="h-full">

<div class="min-h-screen auth-bg relative overflow-hidden flex flex-col">
  <!-- Decorative blobs -->
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-3xl translate-x-1/4 translate-y-1/4"></div>
    <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
      <defs><pattern id="grid-login" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
      <rect width="100%" height="100%" fill="url(#grid-login)"/>
    </svg>
  </div>

  <!-- Top Bar -->
  <div class="relative z-10 px-6 py-5 flex items-center justify-between max-w-7xl mx-auto w-full">
    <a href="{{ route('home') }}" class="flex items-center gap-2 text-blue-200 hover:text-white transition text-sm font-medium">
      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      Kembali ke Beranda
    </a>
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.5" fill="#5a82ff"/><rect x="14" y="3" width="7" height="7" rx="1.5" fill="#8eaeff"/><rect x="3" y="14" width="7" height="7" rx="1.5" fill="#8eaeff"/><rect x="14" y="14" width="7" height="7" rx="1.5" fill="#5a82ff"/></svg>
      </div>
      <span class="text-white font-bold text-sm">SIPANDU</span>
    </div>
  </div>

  <!-- Main Content -->
  <div class="relative z-10 flex-1 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-5xl flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

      <!-- LEFT: Info Panel -->
      <div class="hidden lg:flex flex-col flex-1 text-white">
        <div class="mb-8 anim-fade-up delay-1">
          <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-5">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
            <span class="text-blue-200 text-xs font-medium">Sistem Aktif 24/7</span>
          </div>
          <h2 class="text-4xl font-extrabold leading-tight mb-4">
            Selamat Datang<br>
            <span class="bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">Kembali!</span>
          </h2>
          <p class="text-blue-200/80 text-base leading-relaxed max-w-sm">
            Masuk ke sistem untuk mengelola dan memantau Barang Milik Negara BPMP Provinsi Gorontalo.
          </p>
        </div>
        <div class="grid grid-cols-2 gap-3 anim-fade-up delay-2">
          <div class="glass rounded-xl p-4">
            <div class="text-2xl font-extrabold text-white mb-0.5">{{ number_format($totalAset ?? 0, 0, ',', '.') }}</div>
            <div class="text-blue-300 text-xs">Item BMN Tercatat</div>
          </div>
          <div class="glass rounded-xl p-4">
            <div class="text-2xl font-extrabold text-white mb-0.5">{{ $persentaseBaik ?? 0 }}%</div>
            <div class="text-blue-300 text-xs">Kondisi Baik</div>
          </div>
          <div class="glass rounded-xl p-4">
            <div class="text-2xl font-extrabold text-white mb-0.5">{{ number_format($penggunaAktif ?? 0, 0, ',', '.') }}</div>
            <div class="text-blue-300 text-xs">Pengguna Aktif</div>
          </div>
          <div class="glass rounded-xl p-4">
            <div class="text-2xl font-extrabold text-white mb-0.5">{{ $peranTersedia ?? 0 }}</div>
            <div class="text-blue-300 text-xs">Peran Terpakai</div>
          </div>
        </div>
        <div class="mt-8 anim-fade-up delay-3 float-anim">
          <div class="bg-white/10 backdrop-blur rounded-2xl border border-white/20 p-4 max-w-xs">
            <div class="flex items-center gap-2 mb-3">
              <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
              <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
              <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
              <span class="text-white/40 text-xs ml-1">Dashboard BMN</span>
            </div>
            <div class="space-y-2">
              <div class="flex justify-between items-center bg-white/10 rounded-lg px-3 py-2"><span class="text-blue-200 text-xs">Aset Tetap</span><span class="text-white text-xs font-bold">856 item</span></div>
              <div class="flex justify-between items-center bg-white/10 rounded-lg px-3 py-2"><span class="text-green-300 text-xs">Persediaan</span><span class="text-white text-xs font-bold">391 item</span></div>
              <div class="flex justify-between items-center bg-white/10 rounded-lg px-3 py-2"><span class="text-yellow-300 text-xs">Sarpras</span><span class="text-white text-xs font-bold">127 unit</span></div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Login Card -->
      <div class="w-full max-w-md anim-slide-in delay-2">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">
          <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-navy-600 to-navy-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-navy-900 mb-1">Masuk ke SIBMN</h2>
            <p class="text-slate-400 text-sm">Masukkan kredensial akun Anda</p>
          </div>

          @if (!empty($alreadyLoggedIn))
            <div class="mb-5 rounded-3xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 text-sm">
              Anda sudah masuk. Logout terlebih dahulu jika ingin login dengan akun lain.
            </div>
          @endif

          <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            <!-- Username -->
            <div class="mb-5">
              <label for="loginUsername" class="block text-sm font-semibold text-navy-800 mb-2">Username</label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </span>
                <input id="loginUsername" name="username" type="text" autocomplete="username"
                  value="{{ old('username') }}"
                  class="auth-input w-full pl-10 pr-4 py-3 border {{ $errors->has('username') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Masukkan username Anda">
              </div>
            </div>

            <!-- Password -->
            <div class="mb-5">
              <div class="flex justify-between items-center mb-2">
                <label for="loginPassword" class="text-sm font-semibold text-navy-800">Password</label>
                <button type="button" class="text-xs text-navy-500 hover:text-navy-700 font-medium transition">Lupa password?</button>
              </div>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>
                <input id="loginPassword" name="password" type="password" autocomplete="current-password"
                  class="auth-input w-full pl-10 pr-12 py-3 border border-slate-200 rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Masukkan password Anda">
                <button type="button" onclick="togglePass('loginPassword','eyeLogin')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy-600 transition">
                  <svg id="eyeLogin" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
            </div>

            <!-- Remember me -->
            <div class="flex items-center gap-2 mb-6">
              <input type="checkbox" name="remember" id="rememberMe" class="custom-check" {{ old('remember') ? 'checked' : '' }}>
              <label for="rememberMe" class="text-sm text-slate-600 cursor-pointer select-none">Ingat saya selama 30 hari</label>
            </div>

            <!-- Error (dari server) -->
            @if ($errors->any())
            <div class="mb-5 flex items-center gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
              <span>{{ $errors->first('username') }}</span>
            </div>
            @endif

            <!-- Submit -->
            <button type="submit" id="loginBtn" class="w-full btn-primary text-white font-bold py-3.5 rounded-xl text-sm flex items-center justify-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
              Masuk Sekarang
            </button>
          </form>

          <!-- Divider -->
          <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-slate-200"></div>
            <span class="text-slate-400 text-xs font-medium">atau coba akun demo</span>
            <div class="flex-1 h-px bg-slate-200"></div>
          </div>

          <!-- Quick fill buttons -->
          {{-- <div class="grid grid-cols-2 gap-2 mb-6">
            <button type="button" onclick="quickFill('Operator','super123')"          class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span>Operator</button>
            <button type="button" onclick="quickFill('kepalabpmp','kepala123')"         class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-purple-500"></span>Kepala BPMP</button>
            <button type="button" onclick="quickFill('kasubag','kasubag123')"           class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-500"></span>Kasubag Umum</button>
            <button type="button" onclick="quickFill('adminpersediaan','persediaan123')" class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Admin Persediaan</button>
            <button type="button" onclick="quickFill('adminsarpras','sarpras123')"      class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-cyan-500"></span>Admin Sarpras</button>
            <button type="button" onclick="quickFill('adminasettetap','aset123')"       class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-orange-500"></span>Admin Aset</button>
            <button type="button" onclick="quickFill('pegawai','pegawai123')"           class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-500"></span>Pegawai</button>
            <button type="button" onclick="quickFill('tamu','tamu123')"                 class="text-xs font-semibold text-navy-700 bg-navy-50 hover:bg-navy-100 border border-navy-200/60 rounded-lg py-2 px-3 transition flex items-center gap-1.5 "><span class="w-2 h-2 rounded-full bg-gray-500"></span>Tamu</button>
          </div> --}}

          <p class="text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-navy-600 font-semibold hover:underline ml-1">Daftar Sekarang</a>
          </p>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer -->
  <div class="relative z-10 text-center pb-6">
    <p class="text-blue-200/40 text-xs">© 2025 SIBMN BPMP Provinsi Gorontalo · Sistem terintegrasi pengelolaan BMN</p>
  </div>
</div>

<!-- Toast container -->
<div id="toastContainer" class="fixed top-4 right-4 z-[300] space-y-2 pointer-events-none"></div>

<script>
  // Auto-fill dari tombol "Login Cepat" di landing page
  (function() {
    const u = sessionStorage.getItem('prefill_u');
    const p = sessionStorage.getItem('prefill_p');
    if (u && p) {
      document.getElementById('loginUsername').value = u;
      document.getElementById('loginPassword').value = p;
      sessionStorage.removeItem('prefill_u');
      sessionStorage.removeItem('prefill_p');
    }
  })();

  // Quick-fill akun demo ke form
  function quickFill(u, p) {
    document.getElementById('loginUsername').value = u;
    document.getElementById('loginPassword').value = p;
  }

  // Toggle show/hide password
  function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    const icon = document.getElementById(iconId);
    icon.innerHTML = isText
      ? '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'
      : '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
  }

  // Loading state saat form di-submit
  document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg> Memuat...';
    btn.disabled = true;
  });

  lucide.createIcons();
</script>
</body>
</html>