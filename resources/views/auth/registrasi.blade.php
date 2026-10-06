<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar – SIPANDU BPMP Gorontalo</title>
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
    .strength-bar { height: 4px; border-radius: 9999px; transition: all 0.3s ease; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #bccfff; border-radius: 3px; }
  </style>
</head>
<body class="h-full">

<div class="min-h-screen auth-bg relative overflow-hidden flex flex-col">
  <div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl translate-x-1/3 -translate-y-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-700/10 rounded-full blur-3xl -translate-x-1/4 translate-y-1/4"></div>
    <svg class="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
      <defs><pattern id="grid-reg" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/></pattern></defs>
      <rect width="100%" height="100%" fill="url(#grid-reg)"/>
    </svg>
  </div>

  <div class="relative z-10 px-6 py-5 flex items-center justify-between max-w-7xl mx-auto w-full">
    <a href="{{ route('home') ?? '/' }}" class="flex items-center gap-2 text-blue-200 hover:text-white transition text-sm font-medium">
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

  <div class="relative z-10 flex-1 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-5xl flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-20">

      <div class="hidden lg:flex flex-col flex-1 text-white">
        <div class="mb-8 anim-fade-up delay-1">
          <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-300"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span class="text-blue-200 text-xs font-medium">Pendaftaran Aman &amp; Terverifikasi</span>
          </div>
          <h2 class="text-4xl font-extrabold leading-tight mb-4">
            Bergabung<br>
            <span class="bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">Bersama Kami</span>
          </h2>
          <p class="text-blue-200/80 text-base leading-relaxed max-w-sm">
            Daftarkan akun Anda untuk mengakses sistem pengelolaan Barang Milik Negara BPMP Provinsi Gorontalo.
          </p>
        </div>

        <div class="space-y-4 anim-fade-up delay-2">
          <div class="flex items-start gap-4">
            <div class="w-9 h-9 rounded-xl bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">1</div>
            <div><p class="text-white font-semibold text-sm">Isi Data Diri</p><p class="text-blue-300 text-xs mt-0.5">Nama lengkap, NIP, dan informasi akun</p></div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-9 h-9 rounded-xl bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">2</div>
            <div><p class="text-white font-semibold text-sm">Pilih Unit Kerja</p><p class="text-blue-300 text-xs mt-0.5">Tentukan unit kerja penugasan Anda</p></div>
          </div>
          <div class="flex items-start gap-4">
            <div class="w-9 h-9 rounded-xl bg-blue-500/30 border border-blue-400/40 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">3</div>
            <div><p class="text-white font-semibold text-sm">Akun Aktif</p><p class="text-blue-300 text-xs mt-0.5">Langsung bisa masuk ke dashboard</p></div>
          </div>
        </div>
      </div>

      <div class="w-full max-w-md anim-slide-in delay-2">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">
          <div class="text-center mb-7">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-navy-900 mb-1">Daftar Akun Pegawai</h2>
            <p class="text-slate-400 text-sm">Registrasi akun Pegawai SIPANDU</p>
          </div>

          {{-- Flash Messages --}}
          @if (session('success'))
          <div class="mb-5 flex items-center gap-2.5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
            <span>{{ session('success') }}</span>
          </div>
          @endif

          @if ($errors->any())
          <div class="mb-5 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-0.5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="mb-4">
              <label for="regName" class="block text-sm font-semibold text-navy-800 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                <input id="regName" name="name" type="text" autocomplete="name"
                  value="{{ old('name') }}" required
                  class="auth-input w-full pl-9 pr-4 py-3 border {{ $errors->has('name') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Nama lengkap Anda">
              </div>
            </div>

            <div class="mb-4">
              <label for="regNIP" class="block text-sm font-semibold text-navy-800 mb-2">NIP/NIK <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="8" x2="16" y1="10" y2="10"/><line x1="8" x2="12" y1="14" y2="14"/></svg></span>
                <input id="regNIP" name="nip" type="text"
                  value="{{ old('nip') }}" required
                  class="auth-input w-full pl-9 pr-4 py-3 border {{ $errors->has('nip') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Nomor Induk Pegawai">
              </div>
            </div>

            <div class="mb-4">
              <label for="regUsername" class="block text-sm font-semibold text-navy-800 mb-2">Username <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg></span>
                <input id="regUsername" name="username" type="text" autocomplete="username"
                  value="{{ old('username') }}"
                  class="auth-input w-full pl-9 pr-4 py-3 border {{ $errors->has('username') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Pilih username unik">
              </div>
            </div>

            <div class="mb-4">
              <label for="regPassword" class="block text-sm font-semibold text-navy-800 mb-2">Password <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                <input id="regPassword" name="password" type="password" autocomplete="new-password"
                  oninput="checkPasswordStrength(this.value)"
                  class="auth-input w-full pl-9 pr-12 py-3 border {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Minimal 8 karakter">
                <button type="button" onclick="togglePass('regPassword','eyeReg')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-navy-600 transition">
                  <svg id="eyeReg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
              </div>
              <div class="mt-2 flex gap-1" id="strengthBars">
                <div class="strength-bar flex-1 bg-slate-200"></div>
                <div class="strength-bar flex-1 bg-slate-200"></div>
                <div class="strength-bar flex-1 bg-slate-200"></div>
                <div class="strength-bar flex-1 bg-slate-200"></div>
              </div>
              <p id="strengthText" class="text-xs text-slate-400 mt-1">Masukkan password untuk melihat kekuatan</p>
            </div>

            <div class="mb-4">
              <label for="regPasswordConfirm" class="block text-sm font-semibold text-navy-800 mb-2">Konfirmasi Password <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                <input id="regPasswordConfirm" name="password_confirmation" type="password" autocomplete="new-password"
                  class="auth-input w-full pl-9 pr-4 py-3 border border-slate-200 rounded-xl text-sm text-navy-900 bg-slate-50 placeholder:text-slate-400"
                  placeholder="Ulangi password">
              </div>
            </div>

            <div class="mb-4">
              <label for="regUnitKerja" class="block text-sm font-semibold text-navy-800 mb-2">Unit Kerja <span class="text-red-400">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
                
                <select id="regUnitKerja" name="unit_kerja_id" required class="auth-input w-full pl-9 pr-4 py-3 border {{ $errors->has('unit_kerja_id') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-navy-900 bg-slate-50 appearance-none cursor-pointer">
                  <option value="">-- Pilih Unit Kerja --</option>
                  @foreach($unitKerjas ?? \App\Models\UnitKerja::orderBy('nama_unit')->get() as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                      {{ $unit->nama_unit }}
                    </option>
                  @endforeach
                </select>

                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg></span>
              </div>
            </div>

            <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800 flex items-start gap-2.5">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 flex-shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
              <span>Pendaftaran ini khusus untuk <strong>Pegawai BPMP Gorontalo</strong>. Akun yang didaftarkan akan otomatis mendapatkan peran sebagai <strong>Pegawai</strong> dan terhubung ke Unit Kerja yang dipilih.</span>
            </div>

            <button type="submit" id="regBtn"
              class="w-full btn-primary text-white font-bold py-3.5 rounded-xl text-sm flex items-center justify-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
              Daftar Sekarang
            </button>
          </form>

          <p class="text-center text-sm text-slate-500 mt-5">
            Sudah punya akun?
            <a href="{{ route('login', [], false) }}" class="text-navy-600 font-semibold hover:underline ml-1">Masuk di sini</a>
          </p>
        </div>
      </div>

    </div>
  </div>

  <div class="relative z-10 text-center pb-6">
    <p class="text-blue-200/40 text-xs">© 2025 SIPANDU BPMP Provinsi Gorontalo · Sistem terintegrasi pengelolaan BMN</p>
  </div>
</div>

<script>
  function togglePass(inputId, iconId) {
    const input = document.getElementById(inputId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    const icon = document.getElementById(iconId);
    icon.innerHTML = isText
      ? '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>'
      : '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
  }

  function checkPasswordStrength(val) {
    const bars  = document.querySelectorAll('#strengthBars .strength-bar');
    const text  = document.getElementById('strengthText');
    if (!val) { resetStrength(); return; }
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors     = ['bg-red-400', 'bg-orange-400', 'bg-yellow-400', 'bg-green-500'];
    const labels     = ['Sangat Lemah', 'Lemah', 'Cukup Kuat', 'Kuat'];
    const textColors = ['text-red-500', 'text-orange-500', 'text-yellow-600', 'text-green-600'];
    bars.forEach((b, i) => { b.className = 'strength-bar flex-1 ' + (i < score ? colors[score - 1] : 'bg-slate-200'); });
    text.className   = 'text-xs mt-1 ' + textColors[score - 1];
    text.textContent = labels[score - 1] || 'Masukkan password';
  }

  function resetStrength() {
    document.querySelectorAll('#strengthBars .strength-bar').forEach(b => b.className = 'strength-bar flex-1 bg-slate-200');
    const t = document.getElementById('strengthText');
    t.className   = 'text-xs text-slate-400 mt-1';
    t.textContent = 'Masukkan password untuk melihat kekuatan';
  }

  lucide.createIcons();
</script>
</body>
</html>
