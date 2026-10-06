<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Pengaturan Akun</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root {
    --primary: #2563eb;
    --primary-light: #3b82f6;
    --primary-dark: #1d4ed8;
    --accent: #06b6d4;
    --accent2: #8b5cf6;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --bg: #f0f4ff;
    --sidebar-bg: #0f172a;
    --sidebar-text: #94a3b8;
    --card-bg: #ffffff;
    --text-primary: #0f172a;
    --text-secondary: #64748b;
    --border: #e2e8f0;
    --radius: 16px;
    --shadow: 0 4px 24px rgba(37,99,235,0.08);
    --shadow-lg: 0 8px 40px rgba(37,99,235,0.14);
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--bg);
    color: var(--text-primary);
    display: flex;
    min-height: 100vh;
  }

  /* MAIN */
  .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; }

  .topbar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 20px 0 24px;
    position: sticky; top: 0; z-index: 50;
    background: var(--bg);
  }
  .breadcrumb { font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
  .breadcrumb span { color: var(--primary); font-weight: 600; }
  .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 12px; }
  .notif-btn {
    width: 40px; height: 40px;
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 10px; display: grid; place-items: center;
    cursor: pointer; position: relative; color: var(--text-secondary); transition: all .2s;
  }
  .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
  .notif-dot { position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; background: var(--danger); border-radius: 50%; border: 1.5px solid var(--card-bg); }
  .role-badge {
    display: flex; align-items: center; gap: 6px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    padding: 8px 14px; border-radius: 10px;
    font-size: 12px; font-weight: 700; color: var(--primary);
  }

  /* CONTENT */
  .content-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 28px;
  }

  /* TABS */
  .tabs-nav {
    display: flex; gap: 4px;
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 5px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
  }
  .tab-btn {
    flex: 1; padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; border: none;
    background: transparent;
    color: var(--text-secondary);
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .2s;
    display: flex; align-items: center; justify-content: center; gap: 7px;
  }
  .tab-btn.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: #fff;
    box-shadow: 0 3px 10px rgba(37,99,235,0.3);
  }
  .tab-btn:hover:not(.active) { background: #f0f4ff; color: var(--text-primary); }
  .tab-btn i { font-size: 14px; }

  .tab-panel { display: none; }
  .tab-panel.active { display: block; }

  /* SECTION CARD */
  .section-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 24px;
  }
  .section-header {
    padding: 22px 28px 18px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
  }
  .section-title { font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  .section-title i { color: var(--primary); }
  .section-sub { font-size: 12px; color: var(--text-secondary); margin-top: 3px; }
  .section-body { padding: 24px 28px; }

  /* AVATAR UPLOAD */
  .avatar-section {
    display: flex; align-items: center; gap: 24px;
    padding: 24px 28px;
    border-bottom: 1px solid var(--border);
  }
  .big-avatar {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #f59e0b, #ef4444);
    border-radius: 20px;
    display: grid; place-items: center;
    font-size: 30px; color: #fff; font-weight: 800;
    box-shadow: 0 6px 20px rgba(245,158,11,0.3);
    flex-shrink: 0;
    position: relative;
  }
  .avatar-edit {
    position: absolute; bottom: -4px; right: -4px;
    width: 24px; height: 24px;
    background: var(--primary);
    border-radius: 7px;
    display: grid; place-items: center;
    font-size: 10px; color: #fff;
    cursor: pointer;
    border: 2px solid #fff;
    box-shadow: 0 2px 6px rgba(37,99,235,0.4);
  }
  .avatar-info h3 { font-size: 18px; font-weight: 700; color: var(--text-primary); }
  .avatar-info p { font-size: 13px; color: var(--text-secondary); margin-top: 4px; }
  .avatar-upload-btn {
    display: inline-flex; align-items: center; gap: 7px;
    margin-top: 10px;
    padding: 8px 16px;
    border-radius: 8px;
    border: 1.5px solid var(--border);
    background: transparent;
    font-size: 12px; font-weight: 600;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all .2s;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .avatar-upload-btn:hover { border-color: var(--primary); color: var(--primary); background: rgba(37,99,235,0.05); }

  /* FORM */
  .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  .form-group { margin-bottom: 18px; }
  .form-label {
    display: flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; color: var(--text-secondary);
    text-transform: uppercase; letter-spacing: .7px;
    margin-bottom: 8px;
  }
  .form-label i { color: var(--primary); font-size: 11px; }
  .form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: var(--text-primary);
    background: #fff;
    transition: all .2s;
    outline: none;
  }
  .form-input:focus, .form-select:focus, .form-textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
  }
  .form-input.readonly { background: #f8faff; color: var(--text-secondary); }
  .form-input::placeholder { color: #b0bcd4; }

  /* PASSWORD STRENGTH */
  .pw-strength { margin-top: 8px; }
  .pw-bars { display: flex; gap: 4px; margin-bottom: 5px; }
  .pw-bar { height: 4px; flex: 1; border-radius: 2px; background: #e2e8f0; transition: background .3s; }
  .pw-bar.weak { background: #ef4444; }
  .pw-bar.medium { background: #f59e0b; }
  .pw-bar.strong { background: #10b981; }
  .pw-label { font-size: 11px; color: var(--text-secondary); }

  /* PASSWORD INPUT WRAPPER */
  .pw-wrapper { position: relative; }
  .pw-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    cursor: pointer; color: var(--text-secondary); font-size: 14px;
    transition: color .2s;
  }
  .pw-toggle:hover { color: var(--primary); }

  /* SIGNATURE */
  .signature-zone {
    border: 2px dashed var(--border);
    border-radius: 12px;
    padding: 40px 20px;
    text-align: center;
    color: var(--text-secondary);
    transition: all .2s;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .signature-zone:hover { border-color: var(--primary); background: rgba(37,99,235,0.02); }
  .sig-icon { font-size: 40px; color: #dde5f9; margin-bottom: 10px; }
  .sig-text { font-size: 14px; font-weight: 600; color: var(--text-secondary); }
  .sig-sub { font-size: 12px; color: #b0bcd4; margin-top: 4px; }
  .sig-formats {
    display: flex; gap: 6px; justify-content: center; margin-top: 12px;
  }
  .sig-fmt { font-size: 10px; background: #f0f4ff; color: var(--primary); padding: 3px 8px; border-radius: 5px; font-weight: 600; }

  /* SAVE BUTTON */
  .save-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px 28px;
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 13px; font-weight: 700;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37,99,235,0.35);
    transition: all .2s;
  }
  .save-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
  .cancel-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 12px 22px;
    background: transparent;
    color: var(--text-secondary);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 13px; font-weight: 600;
    font-family: 'Plus Jakarta Sans', sans-serif;
    cursor: pointer;
    transition: all .2s;
  }
  .cancel-btn:hover { border-color: var(--text-secondary); color: var(--text-primary); }
  .btn-row { display: flex; align-items: center; justify-content: flex-end; gap: 10px; margin-top: 8px; }

  /* RIGHT SIDEBAR CARDS */
  .info-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 20px;
  }
  .info-card-header {
    padding: 18px 20px 14px;
    border-bottom: 1px solid var(--border);
    font-family: 'Space Grotesk', sans-serif;
    font-size: 14px; font-weight: 700;
    display: flex; align-items: center; gap: 7px;
    color: var(--text-primary);
  }
  .info-card-header i { color: var(--primary); }
  .info-card-body { padding: 16px 20px; }
  .info-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
  .info-row:last-child { margin-bottom: 0; }
  .info-label { font-size: 12px; color: var(--text-secondary); font-weight: 500; }
  .info-value { font-size: 12px; font-weight: 700; color: var(--text-primary); }
  .info-badge {
    font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 6px;
  }
  .info-badge.role { background: rgba(37,99,235,0.1); color: var(--primary); }
  .info-badge.active { background: rgba(16,185,129,0.1); color: var(--success); }
  .info-badge.approved { background: rgba(16,185,129,0.1); color: var(--success); }

  /* QUICK ACTIONS */
  .quick-list { padding: 14px 20px; display: flex; flex-direction: column; gap: 8px; }
  .quick-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    border-radius: 9px;
    font-size: 13px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    color: var(--text-secondary);
    border: 1px solid var(--border);
    transition: all .2s;
  }
  .quick-item:hover { background: #f0f4ff; border-color: #bfcfff; color: var(--primary); }
  .quick-item i { width: 16px; text-align: center; color: var(--primary); }

  /* SECURITY TIPS */
  .tip-card {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1px solid #fde68a;
    border-radius: 12px;
    padding: 16px;
    display: flex; gap: 12px;
    margin-bottom: 20px;
  }
  .tip-icon { font-size: 18px; color: var(--warning); flex-shrink: 0; margin-top: 2px; }
  .tip-title { font-size: 13px; font-weight: 700; color: #92400e; margin-bottom: 4px; }
  .tip-text { font-size: 12px; color: #b45309; line-height: 1.5; }

  @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
  .animate { animation: fadeUp .5s ease both; }
  .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .15s; }

  ::-webkit-scrollbar { width: 5px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div class="breadcrumb"><a href="{{ route('home') }}" style="text-decoration:none;color:var(--text-secondary)">Dashboard</a> <i class="fas fa-chevron-right" style="font-size:10px"></i> <span>Pengaturan Akun</span></div>
      <div class="topbar-title">Pengaturan Akun</div>
    </div>
    <div class="topbar-right">
      <div class="role-badge"><i class="fas fa-user-shield"></i> Role: {{ auth()->user()->role_label }}</div>
      <div class="notif-btn"><i class="fas fa-bell"></i><div class="notif-dot"></div></div>
    </div>
  </div>

  <div class="content-grid">
    <div>  
  <div class="tabs-nav animate d1">
    <button class="tab-btn active" onclick="switchTab(this,'profil')">
      <i class="fas fa-user"></i> Informasi Profil
    </button>
    <button class="tab-btn" onclick="switchTab(this,'keamanan')">
      <i class="fas fa-shield-halved"></i> Keamanan
    </button>
    <button class="tab-btn" onclick="switchTab(this,'ttd')">
      <i class="fas fa-signature"></i> Tanda Tangan
    </button>
  </div>

  {{-- ========================================== --}}
  {{-- TAB 1: PROFIL - FORM UPDATE PROFILE --}}
  {{-- ========================================== --}}
  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="tab-panel active" id="tab-profil">
      <div class="section-card animate d2">
        <div class="avatar-section">
          <div class="big-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            <div class="avatar-edit"><i class="fas fa-pen"></i></div>
          </div>
          <div class="avatar-info">
            <h3>{{ auth()->user()->name }}</h3>
            <p>
              {{ auth()->user()->jabatan ?? 'BPMP Provinsi Gorontalo' }} 
              - NIP: {{ auth()->user()->nip ?? '-' }}
            </p>
            <label for="signature" class="avatar-upload-btn">
              <i class="fas fa-camera"></i> Ubah Foto
            </label>
            <input type="file" id="signature" name="signature" accept="image/*" style="display:none">
          </div>
        </div>

        <div class="section-body">
          <div class="form-grid-2">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-user"></i> Nama Lengkap</div>
              <input type="text" class="form-input" name="name" value="{{ old('name', auth()->user()->name) }}" required>
              @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
              <div class="form-label"><i class="fas fa-id-badge"></i> Username</div>
              <input type="text" class="form-input readonly" value="{{ auth()->user()->username }}" readonly>
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-building"></i> Jabatan</div>
              <input type="text" class="form-input" name="jabatan" value="{{ old('jabatan', auth()->user()->jabatan) }}">
            </div>
            <div class="form-group">
              <div class="form-label"><i class="fas fa-id-card"></i> NIP</div>
              <input type="text" class="form-input" name="nip" value="{{ old('nip', auth()->user()->nip) }}" maxlength="30">
              @error('nip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
          </div>
          <div class="form-grid-2">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-envelope"></i> Email</div>
              <input type="email" class="form-input readonly" value="{{ auth()->user()->email ?? 'Belum diisi' }}" readonly>
            </div>
            <div class="form-group">
              <div class="form-label"><i class="fas fa-calendar-check"></i> Bergabung</div>
              <input type="text" class="form-input readonly" value="{{ auth()->user()->created_at?->format('d M Y') }}" readonly>
            </div>
          </div>

          <div class="form-grid-2">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-users"></i> Unit Kerja</div>
              <select name="unit_kerja_id" class="form-select cursor-pointer">
                <option value="">-- Belum memilih unit kerja --</option>
                @foreach(\App\Models\UnitKerja::all() as $unit)
                  <option value="{{ $unit->id }}" {{ old('unit_kerja_id', auth()->user()->unit_kerja_id) == $unit->id ? 'selected' : '' }}>
                    {{ $unit->nama_unit }}
                  </option>
                @endforeach
              </select>
              @error('unit_kerja_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
          </div>

          <div class="btn-row">
            <button type="button" class="cancel-btn" onclick="history.back()">
              <i class="fas fa-xmark"></i> Batal
            </button>
            <button type="submit" class="save-btn">
              <i class="fas fa-floppy-disk"></i> Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>

  {{-- ========================================== --}}
  {{-- TAB 2: KEAMANAN - FORM CHANGE PASSWORD --}}
  {{-- ========================================== --}}
  <form method="POST" action="{{ route('password.change') }}">
    @csrf
    <div class="tab-panel" id="tab-keamanan">
      <div class="tip-card animate d2">
        <div class="tip-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div>
          <div class="tip-title">Tips Keamanan Akun</div>
          <div class="tip-text">Gunakan password kuat (min 8 karakter: huruf besar, angka, simbol).</div>
        </div>
      </div>

      <div class="section-card animate d2">
        <div class="section-header">
          <div class="section-title"><i class="fas fa-lock"></i> Ubah Password</div>
        </div>
        <div class="section-body">
          <div class="form-group">
            <div class="form-label"><i class="fas fa-lock"></i> Password Saat Ini</div>
            <div class="pw-wrapper">
              <input type="password" class="form-input" name="current_password" placeholder="Masukkan password lama" id="pwCurrent" required>
              <span class="pw-toggle" onclick="togglePw('pwCurrent',this)"><i class="fas fa-eye"></i></span>
            </div>
            @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>
          
          <div class="form-group">
            <div class="form-label"><i class="fas fa-key"></i> Password Baru</div>
            <div class="pw-wrapper">
              <input type="password" class="form-input" name="new_password" placeholder="Minimal 8 karakter" id="pwNew" required minlength="8" oninput="checkStrength(this.value)">
              <span class="pw-toggle" onclick="togglePw('pwNew',this)"><i class="fas fa-eye"></i></span>
            </div>
            <div class="pw-strength" id="pwStrength" style="display:none">
              <div class="pw-bars">
                <div class="pw-bar" id="bar1"></div><div class="pw-bar" id="bar2"></div>
                <div class="pw-bar" id="bar3"></div><div class="pw-bar" id="bar4"></div>
              </div>
              <div class="pw-label" id="pwLabel">Lemah</div>
            </div>
            @error('new_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>
          
          <div class="form-group">
            <div class="form-label"><i class="fas fa-shield-check"></i> Konfirmasi Password</div>
            <div class="pw-wrapper">
              <input type="password" class="form-input" name="new_password_confirmation" placeholder="Ulangi password baru" id="pwConfirm" required>
              <span class="pw-toggle" onclick="togglePw('pwConfirm',this)"><i class="fas fa-eye"></i></span>
            </div>
          </div>
          
          <div class="btn-row">
            <button type="button" class="cancel-btn" onclick="switchTab(document.querySelector('.tab-btn.active'), 'profil')">
              Batal
            </button>
            <button type="submit" class="save-btn">
              <i class="fas fa-shield-halved"></i> Update Password
            </button>
          </div>
        </div>
      </div>

      <div class="section-card animate d3">
        <div class="section-header">
          <div class="section-title"><i class="fas fa-desktop"></i> Sesi Aktif</div>
        </div>
        <div class="section-body">
          <div style="display:flex;align-items:center;gap:14px;padding:14px;background:#f8faff;border-radius:11px;border:1px solid #e8eeff">
            <div style="width:42px;height:42px;background:rgba(37,99,235,0.1);border-radius:11px;display:grid;place-items:center;color:#2563eb;font-size:18px;flex-shrink:0">
              <i class="fas fa-display"></i>
            </div>
            <div style="flex:1">
              <div style="font-size:13px;font-weight:700;color:var(--text-primary)">Windows PC — Edge</div>
              <div style="font-size:11px;color:var(--text-secondary);margin-top:2px">
                {{ request()->ip() }} · Aktif sekarang
              </div>
            </div>
            <div style="font-size:11px;font-weight:700;background:rgba(16,185,129,0.1);color:#10b981;padding:4px 10px;border-radius:6px">
              <i class="fas fa-circle" style="font-size:7px"></i> Sesi Ini
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  {{-- ========================================== --}}
  {{-- TAB 3: TANDA TANGAN - FORM SIGNATURE --}}
  {{-- ========================================== --}}
  <form method="POST" action="{{ route('profile.signature') }}" enctype="multipart/form-data">
    @csrf
    <div class="tab-panel" id="tab-ttd">
      <div class="section-card animate d2">
        <div class="section-header">
          <div class="section-title"><i class="fas fa-signature"></i> Tanda Tangan Digital</div>
          <div class="section-sub">Tanda tangan akan digunakan pada surat peminjaman gedung</div>
        </div>
        <div class="section-body">
          @if(auth()->user()->signature)
            <div style="margin-bottom:20px">
              <div class="form-label"><i class="fas fa-image"></i> Tanda Tangan Saat Ini</div>
              <img src="{{ Storage::url(auth()->user()->signature) }}" style="max-height:120px;max-width:100%;border-radius:8px;object-fit:contain" alt="Signature">
            </div>
          @else
            <div style="margin-bottom:20px">
              <div class="form-label"><i class="fas fa-image"></i> Tanda Tangan Saat Ini</div>
              <div class="signature-zone" id="sigZone">
                <div class="sig-icon"><i class="fas fa-pen-fancy"></i></div>
                <div class="sig-text">Belum ada tanda tangan</div>
                <div class="sig-sub">Upload file tanda tangan Anda di bawah</div>
              </div>
            </div>
          @endif

          <div style="margin-bottom:20px">
            <div class="form-label"><i class="fas fa-upload"></i> Upload Tanda Tangan Baru</div>
            <label for="signature_upload" style="
              border: 1.5px dashed var(--border); border-radius: 11px; padding: 22px;
              text-align: center; cursor: pointer; transition: all .2s; background: #fafbff;
              display: block;
            " onmouseover="this.style.borderColor='#2563eb';this.style.background='#f0f4ff'"
               onmouseout="this.style.borderColor='var(--border)';this.style.background='#fafbff'">
              <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:#dde5f9;margin-bottom:8px;display:block"></i>
              <div style="font-size:13px;font-weight:600;color:var(--text-secondary)">Klik untuk upload</div>
              <div style="margin-top:8px">
                <span class="sig-fmt">JPG/PNG</span><span class="sig-fmt">Maks. 2MB</span>
              </div>
            </label>
            <input type="file" id="signature_upload" name="signature" accept=".jpg,.jpeg,.png" style="display:none" onchange="previewSig(this)">
          </div>

          <div style="margin-bottom:20px">
            <div class="form-label"><i class="fas fa-pen-to-square"></i> Atau Gambar Manual</div>
            <canvas id="sigCanvas" width="600" height="160" style="
              border: 1.5px solid var(--border); border-radius: 11px; background: #fff;
              cursor: crosshair; display: block; width: 100%; touch-action: none;
            "></canvas>
            <div style="display:flex;gap:8px;margin-top:8px">
              <button type="button" onclick="clearCanvas()" class="btn-small cancel-btn" style="padding: 6px 12px; font-size:12px;">
                <i class="fas fa-eraser"></i> Hapus
              </button>
            </div>
          </div>

          <div class="btn-row">
            <button type="button" class="cancel-btn" onclick="switchTab(document.querySelector('.tab-btn.active'), 'profil')">
              Batal
            </button>
            <button type="submit" class="save-btn">
              <i class="fas fa-floppy-disk"></i> Simpan Tanda Tangan
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<aside style="width:320px">
  <div class="info-card animate d2">
    <div class="info-card-header"><i class="fas fa-circle-info"></i> Informasi Akun</div>
    <div class="info-card-body">
      <div class="info-row">
        <div class="info-label">Role</div>
        <div class="info-badge role">
          <i class="fas fa-user"></i> {{ auth()->user()->role_label }}
        </div>
      </div>
      
      <div class="info-row">
        <div class="info-label">Unit Kerja</div>
        <div class="info-value" style="text-align: right; max-width: 60%; line-height: 1.3;">
          {{ auth()->user()->unitKerja->nama_unit ?? 'Belum diatur' }}
        </div>
      </div>

      <div class="info-row">
        <div class="info-label">Status</div>
        <div class="info-badge {{ auth()->user()->is_active ? 'active' : 'inactive' }}">
          <i class="fas fa-circle" style="font-size:7px"></i> 
          {{ auth()->user()->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </div>
      </div>
      <div class="info-row">
        <div class="info-label">Kelengkapan Profil</div>
        <div class="info-value">{{ auth()->user()->profile_completeness }}%</div>
      </div>
      <div class="info-row">
        <div class="info-label">Bergabung</div>
        <div class="info-value">{{ auth()->user()->created_at?->format('d M Y, H:i') }}</div>
      </div>
      <div class="info-row">
        <div class="info-label">Terakhir Update</div>
        <div class="info-value">{{ auth()->user()->updated_at?->format('d M Y, H:i') }}</div>
      </div>
    </div>
  </div>

  <div class="info-card animate d3">
    <div class="info-card-header"><i class="fas fa-bolt"></i> Aksi Cepat</div>
    <div class="quick-list">
      <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="quick-item">
        <i class="fas fa-chart-line"></i> Dashboard
      </a>
      <a href="#" class="quick-item">
        <i class="fas fa-cog"></i> Pengaturan Akun
      </a>
      <form method="POST" action="{{ route('logout', [], false) }}" style="display:contents">
        @csrf
        <button type="submit" class="quick-item" style="border:none;background:none;width:100%;text-align:left;color:#ef4444">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </div>
  </div>
</aside>
</div>
</main>

<div id="toast" style="
  position:fixed; bottom:28px; right:28px;
  background:#0f172a; color:#fff;
  padding:14px 20px; border-radius:12px;
  font-size:13px; font-weight:600;
  display:flex; align-items:center; gap:10px;
  transform:translateY(80px); opacity:0;
  transition:all .35s cubic-bezier(.4,0,.2,1);
  z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,0.25);
  pointer-events:none;
">
  <i class="fas fa-circle-check" style="color:#10b981;font-size:16px" id="toastIcon"></i>
  <span id="toastMsg">Berhasil disimpan!</span>
</div>

@if(session('success'))
<script>
  document.addEventListener("DOMContentLoaded", function() {
    showToast("{{ session('success') }}");
  });
</script>
@endif

<script>
// TABS
function switchTab(el, id) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  el.classList.add('active');
  document.getElementById('tab-' + id).classList.add('active');
}

// PASSWORD TOGGLE
function togglePw(id, el) {
  const input = document.getElementById(id);
  const isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  el.querySelector('i').className = isText ? 'fas fa-eye' : 'fas fa-eye-slash';
}

// PASSWORD STRENGTH
function checkStrength(val) {
  const s = document.getElementById('pwStrength');
  const lbl = document.getElementById('pwLabel');
  const bars = ['bar1','bar2','bar3','bar4'];
  if (!val) { s.style.display = 'none'; return; }
  s.style.display = 'block';

  let score = 0;
  if (val.length >= 8) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^a-zA-Z0-9]/.test(val)) score++;

  const colors = ['','weak','medium','medium','strong'];
  const labels = ['','Sangat Lemah','Cukup','Baik','Kuat 🔒'];
  const labelColors = ['','#ef4444','#f59e0b','#f59e0b','#10b981'];
  bars.forEach((b, i) => {
    const el = document.getElementById(b);
    el.className = 'pw-bar';
    if (i < score) el.classList.add(colors[score]);
  });
  lbl.textContent = labels[score] || 'Lemah';
  lbl.style.color = labelColors[score] || '#ef4444';
}

// CANVAS SIGNATURE
const canvas = document.getElementById('sigCanvas');
const ctx = canvas.getContext('2d');
let drawing = false;
canvas.addEventListener('mousedown', e => { drawing = true; ctx.beginPath(); ctx.moveTo(e.offsetX, e.offsetY); });
canvas.addEventListener('mousemove', e => {
  if (!drawing) return;
  ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.strokeStyle = '#0f172a';
  ctx.lineTo(e.offsetX, e.offsetY); ctx.stroke();
});
canvas.addEventListener('mouseup', () => drawing = false);
canvas.addEventListener('mouseleave', () => drawing = false);

function clearCanvas() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
}

// FILE PREVIEW
function previewSig(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      const zone = document.getElementById('sigZone');
      zone.innerHTML = `<img src="${e.target.result}" style="max-height:120px;max-width:100%;border-radius:8px;object-fit:contain">`;
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// TOAST
function showToast(msg, type='success') {
  document.getElementById('toastMsg').textContent = msg;
  document.getElementById('toastIcon').style.color = type === 'error' ? '#ef4444' : '#10b981';
  const t = document.getElementById('toast');
  t.style.transform = 'translateY(0)'; t.style.opacity = '1';
  setTimeout(() => { t.style.transform = 'translateY(80px)'; t.style.opacity = '0'; }, 3200);
}
</script>
</body>
</html>
