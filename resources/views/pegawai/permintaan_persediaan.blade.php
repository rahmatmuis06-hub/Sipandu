<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Permintaan Persediaan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
  :root { --primary: #2563eb; --primary-light: #3b82f6; --accent: #06b6d4; --accent2: #8b5cf6; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --bg: #f0f4ff; --sidebar-bg: #0f172a; --sidebar-text: #94a3b8; --card-bg: #ffffff; --text-primary: #0f172a; --text-secondary: #64748b; --border: #e2e8f0; --radius: 16px; --radius-sm: 10px; --shadow: 0 4px 24px rgba(37,99,235,0.08); --shadow-lg: 0 8px 40px rgba(37,99,235,0.14); }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text-primary); display: flex; min-height: 100vh; overflow-x: hidden; }

  /* Kustomisasi Select2 Tema SIPANDU */
  .select2-container .select2-selection--single { height: 42px !important; border: 1.5px solid var(--border) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; display: flex; align-items: center; transition: all .2s; }
  .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--text-primary) !important; padding-left: 14px !important; line-height: normal !important; }
  .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 10px !important; }
  .select2-dropdown { border: 1.5px solid var(--primary) !important; border-radius: 10px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; box-shadow: var(--shadow-lg) !important; }
  .select2-container--default.select2-container--focus .select2-selection--single { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important; }
  .select2-container--default .select2-search--dropdown .select2-search__field { border-radius: 6px !important; border: 1px solid var(--border) !important; padding: 8px 12px !important; }

  /* RESPONSIVE */
  @media (max-width: 1024px) { .main { margin-left: 0 !important; padding: 16px; } .content-grid { grid-template-columns: 1fr !important; gap: 20px; } }
  @media (max-width: 768px) { .input-row { grid-template-columns: 1fr !important; } .history-header { flex-direction: column; gap: 12px; align-items: stretch; } }

  /* MAIN & TOPBAR */
  .main { margin-left: 260px; flex: 1; padding: 0 32px 40px; }
  .topbar { display: flex; align-items: center; justify-content: space-between; padding: 20px 0 24px; position: sticky; top: 0; z-index: 50; background: var(--bg); border-bottom: 1px solid transparent; }
  .topbar-left { display: flex; align-items: center; gap: 14px; }
  .breadcrumb { font-size: 13px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px; }
  .breadcrumb span { color: var(--primary); font-weight: 600; }
  .topbar-title { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 12px; }
  .notif-btn { width: 40px; height: 40px; background: var(--card-bg); border: 1px solid var(--border); border-radius: 10px; display: grid; place-items: center; cursor: pointer; position: relative; color: var(--text-secondary); transition: all .2s; }
  .notif-btn:hover { border-color: var(--primary); color: var(--primary); }
  .notif-dot { position: absolute; top: 8px; right: 8px; width: 7px; height: 7px; background: var(--danger); border-radius: 50%; border: 1.5px solid var(--card-bg); }

  /* LAYOUT & FORM */
  .content-grid { display: grid; grid-template-columns: 1fr 1.4fr; gap: 28px; }
  .form-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; position: sticky; top: 90px; height: fit-content; }
  .form-header { padding: 24px 28px 20px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); position: relative; overflow: hidden; }
  .form-header::before { content: ''; position: absolute; right: -30px; top: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.08); }
  .form-header-icon { position: relative; z-index: 1; width: 46px; height: 46px; background: rgba(255,255,255,0.2); border-radius: 13px; display: grid; place-items: center; font-size: 20px; color: #fff; margin-bottom: 12px; backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.2); }
  .form-header-title { font-family: 'Space Grotesk', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
  .form-header-sub { font-size: 12px; color: rgba(255,255,255,0.75); margin-top: 4px; }
  .form-body { padding: 24px 28px; }
  .form-group { margin-bottom: 18px; }
  .form-label { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 8px; }
  .form-label i { color: var(--primary); font-size: 11px; }
  .form-label .req { color: var(--danger); }
  .form-input, .form-select, .form-textarea { width: 100%; padding: 11px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 13px; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); background: #fff; transition: all .2s; outline: none; }
  .form-input:focus, .form-textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
  .form-input::placeholder, .form-textarea::placeholder { color: #b0bcd4; }
  .form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer; }
  .form-textarea { resize: vertical; min-height: 90px; }
  .input-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  
  /* FACILITY PREVIEW */
  .facility-preview { display: none; margin-top: 8px; padding: 12px 14px; border-radius: 10px; background: linear-gradient(135deg, #eff4ff, #f0fdff); border: 1px solid #c7d7ff; }
  .facility-preview.show { display: flex; align-items: center; gap: 12px; }
  .fp-icon { width: 36px; height: 36px; border-radius: 9px; background: var(--primary); display: grid; place-items: center; color: #fff; font-size: 15px; flex-shrink: 0; }
  .fp-name { font-size: 13px; font-weight: 700; color: var(--text-primary); }
  .fp-details { display: flex; gap: 10px; margin-top: 3px; }
  .fp-tag { font-size: 10px; background: rgba(37,99,235,0.1); color: var(--primary); padding: 2px 8px; border-radius: 5px; font-weight: 600; }
  .submit-btn { width: 100%; padding: 13px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: #fff; border: none; border-radius: 11px; font-size: 14px; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all .2s; box-shadow: 0 4px 14px rgba(37,99,235,0.35); margin-top: 8px; }
  .submit-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
  .submit-btn:active { transform: translateY(0); }
  .submit-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

  /* RIWAYAT CARD & STATUS BADGES */
  .history-card { background: var(--card-bg); border-radius: var(--radius); border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; }
  .history-header { padding: 22px 28px 18px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); }
  .history-title { font-family: 'Space Grotesk', sans-serif; font-size: 17px; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 8px; }
  .history-title i { color: var(--primary); }
  .filter-tabs { display: flex; gap: 6px; }
  .filter-tab { font-size: 11px; font-weight: 600; padding: 5px 12px; border-radius: 7px; cursor: pointer; border: 1.5px solid var(--border); background: transparent; color: var(--text-secondary); transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif; }
  .filter-tab.active { background: var(--primary); color: #fff; border-color: var(--primary); }
  .filter-tab:hover:not(.active) { border-color: var(--primary); color: var(--primary); }
  .req-list { padding: 20px 28px; display: flex; flex-direction: column; gap: 16px; }
  .req-card { border: 1.5px solid var(--border); border-radius: 14px; overflow: hidden; transition: all .2s; }
  .req-card:hover { box-shadow: var(--shadow); transform: translateY(-1px); }
  .req-card-top { padding: 16px 18px; display: flex; align-items: flex-start; justify-content: space-between; }
  .req-card-icon { width: 42px; height: 42px; border-radius: 11px; display: grid; place-items: center; font-size: 17px; flex-shrink: 0; }
  .req-card-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
  .req-card-code { font-size: 11px; color: var(--text-secondary); margin-top: 2px; }
  
  .status-badge { font-size: 11.5px; font-weight: 700; padding: 5px 12px; border-radius: 8px; letter-spacing: .3px; display: flex; align-items: center; gap: 6px; white-space: nowrap; }
  .status-badge.pending, .status-badge.review { background: rgba(245,158,11,0.12); color: var(--warning); border: 1px solid rgba(245,158,11,0.25); }
  .status-badge.approved { background: rgba(16,185,129,0.12); color: var(--success); border: 1px solid rgba(16,185,129,0.25); }
  .status-badge.rejected { background: rgba(239,68,68,0.12); color: var(--danger); border: 1px solid rgba(239,68,68,0.25); }
  .status-badge.cancelled { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
  .status-badge i { font-size: 10px; }

  .req-card-meta { padding: 12px 18px; background: #f8faff; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; border-top: 1px solid #eef1ff; }
  .meta-label { font-size: 10px; text-transform: uppercase; letter-spacing: .6px; color: #94a3b8; font-weight: 700; margin-bottom: 3px; }
  .meta-value { font-size: 12px; font-weight: 600; color: var(--text-primary); }
  .req-card-footer { padding: 12px 18px; display: flex; gap: 8px; border-top: 1px solid #eef1ff; }
  .card-btn { flex: 1; padding: 9px; border-radius: 8px; font-size: 12px; font-weight: 600; cursor: pointer; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; font-family: 'Plus Jakarta Sans', sans-serif; transition: all .2s; text-decoration: none; }
  .card-btn.detail { background: rgba(37,99,235,0.08); color: var(--primary); }
  .card-btn.detail:hover { background: rgba(37,99,235,0.15); }
  .card-btn.cancel { background: rgba(239,68,68,0.08); color: var(--danger); }
  .card-btn.cancel:hover { background: rgba(239,68,68,0.15); }
  .card-btn.surat { background: rgba(16,185,129,0.08); color: var(--success); }
  .card-btn.surat:hover { background: rgba(16,185,129,0.15); }
  .empty-state { text-align: center; padding: 60px 20px; }
  .empty-icon { font-size: 56px; color: #dde5f9; margin-bottom: 14px; }
  .empty-text { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 6px; }
  .empty-sub { font-size: 13px; color: var(--text-secondary); }

  /* MODAL & UTILS */
  #detailModal { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.75); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9998; padding: 20px; }
  #detailModal.show { display: flex !important; }
  #detailModal .modal-content { background: var(--card-bg); border-radius: var(--radius); width: 100%; max-width: 620px; max-height: 90vh; overflow-y: auto; box-shadow: var(--shadow-lg); border: 1px solid var(--border); position: relative; animation: fadeUp .3s ease; }
  @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
  .animate { animation: fadeUp .5s ease both; } .d1 { animation-delay: .05s; } .d2 { animation-delay: .1s; } .d3 { animation-delay: .15s; } .d4 { animation-delay: .2s; } .d5 { animation-delay: .25s; }
  ::-webkit-scrollbar { width: 5px; } ::-webkit-scrollbar-track { background: transparent; } ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
  @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
  
  /* TOAST */
  #toast { position:fixed; bottom:28px; right:28px; background:#0f172a; color:#fff; padding:14px 20px; border-radius:12px; font-size:13px; font-weight:600; display:flex; align-items:center; gap:10px; transform:translateY(80px); opacity:0; transition:all .35s cubic-bezier(.4,0,.2,1); z-index:9999; box-shadow:0 8px 24px rgba(0,0,0,0.25); pointer-events:none; }
  #toast.show { transform:translateY(0); opacity:1; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <div class="topbar-left">
      <div>
        <div class="breadcrumb">
          <a href="{{ route('pegawai.dashboard') }}" style="text-decoration:none;color:var(--text-secondary)">Dashboard</a> 
          <i class="fas fa-chevron-right" style="font-size:10px"></i> 
          <span>Permintaan Persediaan</span>
        </div>
        <div class="topbar-title">Permintaan Persediaan</div>
      </div>
    </div>
    <div class="topbar-right">
      {{-- <div class="notif-btn" onclick="alert('Notifikasi akan datang!')"><i class="fas fa-bell"></i><div class="notif-dot"></div></div> --}}
    </div>
  </div>

  <div class="content-grid">
    <div class="form-card animate d2" id="formCard">
      <div class="form-header">
        <div class="form-header-icon"><i class="fas fa-boxes-stacked"></i></div>
        <div class="form-header-title">Buat Permintaan Baru</div>
        <div class="form-header-sub">Pilih barang dari persediaan yang tersedia</div>
      </div>
      
      <form method="POST" action="{{ route('pegawai.permintaan-persediaan.store') }}" id="permintaanForm">
        @csrf
        <div class="form-body">
          
          <div class="form-group">
            <div class="form-label"><i class="fas fa-box"></i> Pilih Barang <span class="req">*</span></div>
            
            <select class="form-select @error('kode_barang') border-red-500 @enderror" 
                    name="persediaan_id" id="persediaanSelect" required>
              <option value="">📦 Ketik untuk mencari barang persediaan...</option>
              @foreach($persediaan as $item)
                <option value="{{ $item-> id }}" 
                        data-kode="{{ $item ->kode_barang }}"
                        data-nama="{{ $item->nama_barang }}" 
                        data-kategori="{{ $item->kategori ?? 'Umum' }}"
                        data-satuan="{{ $item->satuan }}"
                        data-stok="{{ $item->jumlah }}">
                  {{ $item->kode_barang }} - {{ $item->nama_barang }} (Satuan: {{ $item->satuan }}) (Stok: {{ number_format($item->jumlah) }})
                </option>
              @endforeach
            </select>
            @error('persediaan_id')
              <div style="font-size:11px;margin-top:4px;color:var(--danger)">{{ $message }}</div>
            @enderror
            
            <div class="facility-preview" id="facilityPreview">
              <div class="fp-icon" id="fpIcon"><i class="fas fa-box"></i></div>
              <div>
                <div class="fp-name" id="fpName">-</div>
                <div class="fp-details">
                  <span class="fp-tag" id="fpKode">-</span>
                  <span class="fp-tag" id="fpSatuan" style="background:rgba(59,130,246,0.1);color:var(--primary)">-</span>
                  <span class="fp-tag" id="fpStok" style="background:rgba(16,185,129,0.1);color:var(--success)">-</span>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label"><i class="fas fa-hashtag"></i> Jumlah Diminta <span class="req">*</span></div>
            <input type="number" 
                   class="form-input @error('jumlah_diminta') border-red-500 @enderror" 
                   name="jumlah_diminta" 
                   id="jumlah_diminta" 
                   min="1" 
                   max="999999"
                   value="{{ old('jumlah_diminta', 1) }}" 
                   placeholder="1" 
                   required>
            
            @error('jumlah_diminta')
              <div class="text-danger" style="font-size:11px;margin-top:4px;color:var(--danger)">{{ $message }}</div>
            @enderror
          <small id="stok-info" class="text-muted" style="font-size:11px;margin-top:4px;display:block;"></small>
            <div id="peringatan-stok" style="display: none; font-size:11px;margin-top:4px;color:var(--danger);background:rgba(239,68,68,0.1);padding:6px 10px;border-radius:6px;border:1px solid rgba(239,68,68,0.2);">
              <i class="fas fa-exclamation-triangle" style="font-size:10px;margin-right:4px;"></i>
              Jumlah permintaan melebihi stok yang tersedia!
            </div>
          </div>

          <input type="hidden" name="satuan" id="hiddenSatuan" value="">
          
          {{-- <div class="form-group">
              <div class="form-label"><i class="fas fa-balance-scale"></i> Satuan <span class="req">*</span></div>
              <select class="form-select @error('satuan') border-red-500 @enderror" name="satuan" required>
                <option value="" disabled selected>-- Pilih Satuan --</option>
                <option value="Lusin" {{ old('satuan') == 'Lusin' ? 'selected' : '' }}>Lusin</option>
                <option value="Rim" {{ old('satuan') == 'Rim' ? 'selected' : '' }}>Rim</option>
                <option value="Buah" {{ old('satuan') == 'Buah' ? 'selected' : '' }}>Buah</option>
                <option value="Dos" {{ old('satuan') == 'Dos' ? 'selected' : '' }}>Dos</option>
                <option value="Paket" {{ old('satuan') == 'Paket' ? 'selected' : '' }}>Paket</option>
                <option value="Pak" {{ old('satuan') == 'Pak' ? 'selected' : '' }}>Pak</option>
                <option value="Unit" {{ old('satuan') == 'Unit' ? 'selected' : '' }}>Unit</option>
                <option value="Karton" {{ old('satuan') == 'Karton' ? 'selected' : '' }}>Karton</option>
                <option value="Box" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                <option value="Set" {{ old('satuan') == 'Set' ? 'selected' : '' }}>Set</option>
              </select>
              @error('satuan')
                <div class="text-danger" style="font-size:11px;margin-top:4px;color:var(--danger)">{{ $message }}</div>
              @enderror
            </div> --}}
          <div class="input-row">
            <div class="form-group">
              <div class="form-label"><i class="fas fa-calendar"></i> Tanggal Permintaan <span class="req">*</span></div>
              <input type="date" class="form-input @error('tanggal_permintaan') border-red-500 @enderror" 
                     name="tanggal_permintaan" value="{{ old('tanggal_permintaan') ?? date('Y-m-d') }}" required>
              @error('tanggal_permintaan')
                <div class="text-danger" style="font-size:11px;margin-top:4px;color:var(--danger)">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <div class="form-label"><i class="fas fa-bullseye"></i> Tujuan Penggunaan <span class="req">*</span></div>
            <textarea class="form-textarea @error('tujuan_penggunaan') border-red-500 @enderror" 
                      name="tujuan_penggunaan" rows="4" placeholder="Jelaskan tujuan penggunaan barang secara singkat dan jelas..."
                      required>{{ old('tujuan_penggunaan') }}</textarea>
            @error('tujuan_penggunaan')
              <div class="text-danger" style="font-size:11px;margin-top:4px;color:var(--danger)">{{ $message }}</div>
            @enderror
          </div>

          @if(session('success'))
            <div class="alert alert-success" style="padding:12px;border-radius:10px;margin-bottom:16px;font-size:13px;display:flex;align-items:center;gap:8px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.2)">
              <i class="fas fa-check-circle" style="color:var(--success)"></i>
              {{ session('success') }}
            </div>
          @endif

          <button type="submit" class="submit-btn" id="submitBtn">
            <i class="fas fa-paper-plane"></i> 
            Kirim Permintaan Persediaan
          </button>
        </div>
      </form>
    </div>

    <div>
      <div class="history-card animate d3">
        <div class="history-header">
          <div class="history-title"><i class="fas fa-clock-rotate-left"></i> Riwayat Permintaan ({{ $riwayat->count() }})</div>
          <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">Semua</button>
            <button class="filter-tab" data-filter="pending">Pending</button>
            <button class="filter-tab" data-filter="approved">Disetujui</button>
            <button class="filter-tab" data-filter="rejected">Ditolak</button>
          </div>
        </div>
        <div class="req-list" id="riwayatList">
          @forelse($riwayat as $item)
            @php
                $badgeClass = 'pending'; $icon = 'fa-clock'; $statusText = 'Menunggu Admin';
                $rawStatus = strtolower($item->status);
                
                if(in_array($rawStatus, ['disetujui', 'disetujui_admin', 'disetujui_kasubag', 'selesai'])) { 
                    $badgeClass = 'approved'; $icon = 'fa-check-circle'; $statusText = 'Disetujui'; 
                } elseif(in_array($rawStatus, ['ditolak', 'rejected'])) { 
                    $badgeClass = 'rejected'; $icon = 'fa-times-circle'; $statusText = 'Ditolak'; 
                } elseif(in_array($rawStatus, ['diteruskan_kasubag', 'dalam_review'])) { 
                    $badgeClass = 'review'; $icon = 'fa-eye'; $statusText = 'Di teruskan kekasubag'; 
                } elseif(in_array($rawStatus, ['dibatalkan', 'cancelled'])) { 
                    $badgeClass = 'rejected'; $icon = 'fa-ban'; $statusText = 'Dibatalkan'; 
                }
            @endphp
            
            <div class="req-card" data-status="{{ $badgeClass }}">
              <div class="req-card-top">
                <div style="display:flex;align-items:center;gap:12px">
                  <div class="req-card-icon" style="background:rgba(37,99,235,0.1);color:#2563eb">
                    <i class="fas fa-box"></i>
                  </div>
                  <div>
                    <div class="req-card-name">{{ $item->persediaan->nama_barang ?? $item->nama_barang }}</div>
                    <div class="req-card-code">REQ-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</div>
                  </div>
                </div>
                
                <div class="status-badge {{ $badgeClass }}">
                  <i class="fas {{ $icon }}"></i> 
                  {{ $statusText }}
                </div>
              </div>

              <div class="req-card-meta">
                <div class="meta-item">
                  <div class="meta-label">Jumlah Diminta</div>
                  <div class="meta-value">{{ $item->jumlah_diminta }} {{ $item->satuan ?? 'Unit' }}</div>
                </div>
                @if($item->jumlah_disetujui !== null)
                <div class="meta-item">
                    <div class="meta-label" style="color: var(--success);">Jumlah Disetujui</div>
                    <div class="meta-value" style="color: var(--success); font-weight: 800;">{{ $item->jumlah_disetujui }} {{ $item->satuan ?? 'Unit' }}</div>
                </div>
                @endif
                <div class="meta-item">
                  <div class="meta-label">Tanggal Permintaan</div>
                  <div class="meta-value">{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</div>
                </div>
                <div class="meta-item">
                  <div class="meta-label">Tujuan</div>
                  <div class="meta-value">{{ Str::limit($item->tujuan_penggunaan, 40) }}</div>
                </div>
              </div>

              <div class="req-card-footer">
                <button class="card-btn detail" onclick="showDetail({{ $item->id }})">
                  <i class="fas fa-eye"></i> Detail
                </button>
                
                @if($rawStatus == 'pending')
                <button class="card-btn cancel" onclick="cancelRequest({{ $item->id }})">
                  <i class="fas fa-xmark"></i> Batalkan
                </button>
                @endif
                
                @if(!empty($item->surat_bast_path))
                <a href="{{ asset('storage/' . $item->surat_bast_path) }}" target="_blank" class="card-btn surat" title="Lihat Surat Persetujuan BAST">
                  <i class="fas fa-file-contract"></i> Surat BAST
                </a>
                @endif
              </div>
            </div>
          @empty
            <div class="empty-state">
              <div class="empty-icon"><i class="fas fa-inbox"></i></div>
              <div class="empty-text">Belum ada riwayat permintaan</div>
              <div class="empty-sub">Buat permintaan persediaan pertama Anda di sebelah kiri</div>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</main>

<div id="detailModal">
  <div class="modal-content">
    <div style="padding: 24px 28px 20px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: white; position: relative; overflow: hidden;">
      <div style="position: absolute; right: 28px; top: 24px; cursor: pointer; font-size: 20px; opacity: 0.8;" onclick="closeModal()">
        <i class="fas fa-times"></i>
      </div>
      <div style="display: flex; align-items: center; gap: 16px;">
        <div style="width: 52px; height: 52px; background: rgba(255,255,255,0.2); border-radius: 14px; display: grid; place-items: center; font-size: 22px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
          <i class="fas fa-boxes-stacked"></i>
        </div>
        <div>
          <div style="font-size: 20px; font-weight: 700; font-family: 'Space Grotesk', sans-serif;">Detail Permintaan</div>
          <div style="font-size: 13px; opacity: 0.9; margin-top: 2px;">REQ-<span id="detailKode">-</span></div>
        </div>
      </div>
      
      <div id="detailStatusBadge" style="position: absolute; top: 28px; right: 28px; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; letter-spacing: .5px; display: flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
        <i class="fas fa-circle-notch fa-spin"></i> Memuat...
      </div>
    </div>

    <div id="detailLoading" style="padding: 60px 28px; text-align: center; display: block;">
      <div style="width: 56px; height: 56px; border: 3px solid rgba(37,99,235,0.1); border-top: 3px solid var(--primary); border-radius: 50%; margin: 0 auto 20px; animation: spin 1s linear infinite;"></div>
      <div style="color: var(--text-secondary); font-size: 14px;">Memuat detail permintaan...</div>
    </div>

    <div id="detailContent" style="display: none; padding: 0;">
      <div style="padding: 28px 28px 0;">
        <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
          <i class="fas fa-user" style="color: var(--primary); font-size: 11px;"></i> Informasi Peminta
        </div>
        <div style="background: #f8faff; padding: 20px; border-radius: 12px; border: 1px solid #eef1ff;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Nama Lengkap</div>
              <div id="detailNama" style="font-size: 16px; font-weight: 700; color: var(--text-primary);">-</div>
            </div>
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">NIP/NIK</div>
              <div id="detailNipNik" style="font-size: 14px; color: var(--text-secondary);">-</div>
            </div>
          </div>
        </div>
      </div>

      <div style="padding: 24px 28px 0;">
        <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
          <i class="fas fa-box" style="color: var(--primary); font-size: 11px;"></i> Detail Persediaan
        </div>
        <div style="background: #f8faff; padding: 20px; border-radius: 12px; border: 1px solid #eef1ff;">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Nama Barang</div>
              <div id="detailFasilitas" style="font-size: 16px; font-weight: 700; color: var(--text-primary);">-</div>
            </div>
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Kode Barang</div>
              <div id="detailKodeBarang" style="font-size: 14px; color: var(--text-secondary);">-</div>
            </div>
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Kategori</div>
              <div id="detailKategori" style="font-size: 14px; color: var(--text-secondary);">-</div>
            </div>
            <div>
              <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;">Jumlah Diminta</div>
              <div id="detailJumlah" style="font-size: 16px; font-weight: 700; color: var(--primary);">-</div>
            </div>
          </div>
        </div>
      </div>

      <div style="padding: 24px 28px 24px;">
        <div style="display: grid; grid-template-columns: 1fr 1.2fr; gap: 24px;">
          <div>
            <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
              <i class="fas fa-calendar" style="color: var(--primary); font-size: 11px;"></i> Jadwal Permintaan
            </div>
            <div style="background: #f8faff; padding: 20px; border-radius: 12px; border: 1px solid #eef1ff;">
              <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                  <div style="font-size: 11px; color: #94a3b8; margin-bottom: 4px;">Tanggal Permintaan</div>
                  <div id="detailTglPermintaan" style="font-weight: 600; color: var(--text-primary);">-</div>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
              <i class="fas fa-bullseye" style="color: var(--primary); font-size: 11px;"></i> Tujuan Penggunaan
            </div>
            <div style="background: #f8faff; padding: 20px; border-radius: 12px; border: 1px solid #eef1ff; min-height: 100%;">
              <div id="detailTujuan" style="font-size: 14px; line-height: 1.6; color: var(--text-primary); white-space: pre-wrap;">-</div>
            </div>
          </div>
        </div>
      </div>

      <div style="padding: 0 28px 28px;">
        <div id="detailReviewedWrap" style="display: none; margin-bottom: 16px;">
          <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-user-check" style="color: var(--success); font-size: 11px;"></i> Reviewer
          </div>
          <div style="background: rgba(16,185,129,0.05); padding: 16px; border-radius: 10px; border: 1px solid rgba(16,185,129,0.15);">
            <div id="detailReviewedBy" style="font-size: 14px; color: var(--success); font-weight: 600;">-</div>
          </div>
        </div>

        <div id="detailApprovedWrap" style="display: none; margin-bottom: 16px;">
          <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-user-tie" style="color: #06b6d4; font-size: 11px;"></i> Approved Kasubag
          </div>
          <div style="background: rgba(6,182,212,0.05); padding: 16px; border-radius: 10px; border: 1px solid rgba(6,182,212,0.15);">
            <div id="detailApprovedBy" style="font-size: 14px; color: #06b6d4; font-weight: 600;">-</div>
          </div>
        </div>

        <div id="detailKomentarWrap" style="display: none;">
          <div style="font-size: 12px; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: .6px; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
            <i class="fas fa-comment" style="color: var(--warning); font-size: 11px;"></i> Komentar Admin
          </div>
          <div style="background: rgba(245,158,11,0.05); padding: 16px; border-radius: 10px; border: 1px solid rgba(245,158,11,0.15);">
            <div id="detailKomentar" style="font-size: 14px; color: var(--text-primary); line-height: 1.6;">-</div>
          </div>
        </div>
      </div>

      <div style="padding: 20px 28px 28px; border-top: 1px solid var(--border); display: flex; gap: 12px; justify-content: space-between;">
        <div style="font-size: 11px; color: var(--text-secondary); display: flex; align-items: center; gap: 6px;">
          <i class="fas fa-clock" style="font-size: 10px;"></i>
          <span id="detailCreatedAt">-</span>
        </div>
        <div style="display: flex; gap: 8px;">
          <a id="detailSuratLink" href="#" style="display: none; padding: 10px 20px; background: rgba(16,185,129,0.08); color: var(--success); border: 1px solid rgba(16,185,129,0.2); border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; align-items: center; gap: 6px; transition: all .2s;" target="_blank">
            <i class="fas fa-file-contract"></i> Lihat Surat BAST
          </a>
          <button onclick="closeModal()" style="padding: 10px 20px; background: transparent; color: var(--text-secondary); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .2s;">
            <i class="fas fa-times"></i> Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="toast">
  <i class="fas fa-circle-check" style="color:#10b981;font-size:16px" id="toastIcon"></i>
  <span id="toastMsg">Permintaan berhasil dikirim!</span>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
class PermintaanController {
  constructor() {
    this.currentStok = 0;
    this.init();
  }

  init() {
    this.initSelect2();
    this.bindEvents();
  }

  initSelect2() {
    // Inisialisasi Select2
    $('#persediaanSelect').select2({
      placeholder: "-- Ketik untuk mencari barang persediaan... --",
      allowClear: true,
      width: '100%'
    });

    // Menghubungkan Select2 dengan fungsi perubahan barang
    $('#persediaanSelect').on('select2:select select2:clear', (e) => {
        // e.target adalah element select asli
        this.handleBarangChange({ target: document.getElementById('persediaanSelect') });
    });
  }

  bindEvents() {
    const jumlahInput = document.getElementById('jumlah_diminta');
    if (jumlahInput) jumlahInput.addEventListener('input', () => this.validateJumlah());

    // 🌟 BARU: Listener untuk mendeteksi pegawai mengganti satuan secara manual
    const satuanSelect = document.getElementById('satuanSelect');
    if (satuanSelect) {
      satuanSelect.addEventListener('change', () => {
        const fpSatuan = document.getElementById('fpSatuan');
        if (fpSatuan) fpSatuan.textContent = satuanSelect.value || '-';
      });
    }

    const form = document.getElementById('permintaanForm');
    if (form) form.addEventListener('submit', (e) => this.handleFormSubmit(e));

    this.bindFilterTabs();
  }

  handleBarangChange(e) {
    const select = e.target;
    // Karena bisa menggunakan select2:clear dimana tidak ada option yg terpilih
    if (select.selectedIndex === -1) {
       this.resetForm();
       return;
    }
    
    const selectedOption = select.options[select.selectedIndex];
    const preview = document.getElementById('facilityPreview');
    const jumlahInput = document.getElementById('jumlah_diminta');
    const stokInfo = document.getElementById('stok-info');
    const peringatan = document.getElementById('peringatan-stok');
    
    // 🌟 BARU: Ambil elemen dropdown satuan
    const satuanSelect = document.getElementById('satuanSelect'); 

    if (selectedOption && selectedOption.value) {
      this.currentStok = parseInt(selectedOption.getAttribute('data-stok')) || 0;
      
      // 🌟 BARU: Ambil satuan dari master data
      const satuanMaster = selectedOption.getAttribute('data-satuan');
      
      // 🌟 BARU: Pilih otomatis dropdown satuan berdasarkan master data
      if (satuanSelect && satuanMaster) {
        let isFound = false;
        Array.from(satuanSelect.options).forEach(opt => {
          if(opt.value.toLowerCase() === satuanMaster.toLowerCase()) {
            opt.selected = true;
            isFound = true;
          }
        });
        if (!isFound) satuanSelect.value = ''; // Kosongkan jika satuan master tidak ada di pilihan dropdown
      }

      document.getElementById('fpName').textContent = selectedOption.dataset.nama || 'N/A';
      document.getElementById('fpKode').textContent = 'Kode: ' + (selectedOption.dataset.kode || selectedOption.value);
      document.getElementById('fpStok').textContent = 'Stok: ' + this.currentStok.toLocaleString();
      
      // 🌟 BARU: Tampilkan satuan yang sedang terpilih ke kotak Preview
      const fpSatuan = document.getElementById('fpSatuan');
      if (fpSatuan) fpSatuan.textContent = (satuanSelect && satuanSelect.value) ? satuanSelect.value : (satuanMaster || '-');

      preview.classList.add('show');

      if (stokInfo) {
        stokInfo.textContent = `Stok tersedia: ${this.currentStok.toLocaleString()}`;
        stokInfo.setAttribute('data-stok', this.currentStok);
      }
      
      if (peringatan) peringatan.style.display = 'none';
      
      if (jumlahInput) {
        jumlahInput.max = this.currentStok;
        jumlahInput.title = `Maksimum ${this.currentStok.toLocaleString()}`;
      }

      this.validateJumlah();
      
    } else {
      this.resetForm();
    }
  }

  resetForm() {
      const preview = document.getElementById('facilityPreview');
      const jumlahInput = document.getElementById('jumlah_diminta');
      const stokInfo = document.getElementById('stok-info');
      const peringatan = document.getElementById('peringatan-stok');
      const satuanSelect = document.getElementById('satuanSelect'); // 🌟 BARU

      if(preview) preview.classList.remove('show');
      this.currentStok = 0;

      // 🌟 BARU: Bersihkan preview satuan dan dropdown satuan
      const fpSatuan = document.getElementById('fpSatuan');
      if (fpSatuan) fpSatuan.textContent = '-';
      if (satuanSelect) satuanSelect.value = '';

      if (jumlahInput) {
        jumlahInput.max = 999999;
        jumlahInput.title = '';
        jumlahInput.value = '1';
      }
      if (stokInfo) stokInfo.textContent = '';
      if (peringatan) peringatan.style.display = 'none';
  }

  validateJumlah() {
    const jumlahInput = document.getElementById('jumlah_diminta');
    const stokInfo = document.getElementById('stok-info');
    const peringatan = document.getElementById('peringatan-stok');

    if (!jumlahInput || !stokInfo || !peringatan) return;

    const jumlah = parseInt(jumlahInput.value) || 0;
    const stokTersedia = this.currentStok;

    if (jumlah > stokTersedia && stokTersedia > 0) {
      peringatan.style.display = 'block';
      jumlahInput.style.borderColor = 'var(--danger)';
      jumlahInput.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.1)';
    } else {
      peringatan.style.display = 'none';
      jumlahInput.style.borderColor = '';
      jumlahInput.style.boxShadow = '';
    }
  }

  handleFormSubmit(e) {
    const persediaanSelect = document.getElementById('persediaanSelect');
    const jumlahInput = document.getElementById('jumlah_diminta');
    
    if (!persediaanSelect?.value) {
      e.preventDefault();
      this.showToast('❌ Silakan pilih barang terlebih dahulu!', 'error');
      // Fokus ke search box Select2
      $('#persediaanSelect').select2('open');
      return false;
    }
    
    const jumlah = parseInt(jumlahInput?.value) || 0;
    if (jumlah > this.currentStok || this.currentStok === 0) {
      e.preventDefault();
      this.showToast(`❌ Jumlah ${jumlah.toLocaleString()} melebihi stok ${this.currentStok.toLocaleString()}!`, 'error');
      jumlahInput?.focus();
      return false;
    }
    
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      submitBtn.disabled = true;
    }
  }

  bindFilterTabs() {
    document.querySelectorAll('.filter-tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.dataset.filter;
        document.querySelectorAll('.req-card').forEach(card => {
          if (filter === 'all' || card.dataset.status === filter) {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        });
      });
    });
  }

  showToast(msg, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = document.getElementById('toastIcon');
    const msgEl = document.getElementById('toastMsg');
    if (!toast || !icon || !msgEl) return;
    
    msgEl.textContent = msg;
    icon.className = type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-circle-check';
    icon.style.color = type === 'error' ? '#ef4444' : '#10b981';
    
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 4000);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  new PermintaanController();
  
  window.showDetail = function(id) {
    const modal = document.getElementById('detailModal');
    const loading = document.getElementById('detailLoading');
    const content = document.getElementById('detailContent');
    
    modal.classList.add('show');
    loading.style.display = 'block';
    content.style.display = 'none';
    
    fetch(`/pegawai/permintaan-persediaan/${id}`, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.populateDetailModal(data.data);
      } else {
        showToast('❌ Gagal memuat detail: ' + (data.message || 'Unknown error'), 'error');
      }
    })
    .catch(error => {
      showToast('❌ Gagal memuat detail permintaan', 'error');
    });
  };

  window.closeModal = function() {
    const modal = document.getElementById('detailModal');
    modal.classList.remove('show');
    document.getElementById('detailLoading').style.display = 'block';
    document.getElementById('detailContent').style.display = 'none';
  };

  window.cancelRequest = function(id) {
    if (!confirm('⚠️ Yakin ingin membatalkan permintaan ini?\n\nPermintaan tidak bisa dikembalikan.')) {
      return;
    }

    const btn = event.target.closest('.card-btn.cancel');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membatalkan...';
    btn.disabled = true;

    fetch(`/pegawai/permintaan-persediaan/${id}/cancel`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showToast('✅ Permintaan berhasil dibatalkan!', 'success');
        const card = btn.closest('.req-card');
        if (card) {
          card.style.opacity = '0.5';
          card.style.pointerEvents = 'none';
          const statusBadge = card.querySelector('.status-badge');
          if (statusBadge) {
            statusBadge.className = 'status-badge cancelled';
            statusBadge.innerHTML = '<i class="fas fa-ban"></i> Dibatalkan';
          }
          const cancelBtn = card.querySelector('.card-btn.cancel');
          if (cancelBtn) cancelBtn.remove();
        }
      } else {
        showToast('❌ Gagal membatalkan: ' + (data.message || 'Unknown error'), 'error');
        btn.innerHTML = originalText;
        btn.disabled = false;
      }
    })
    .catch(error => {
      showToast('❌ Gagal membatalkan permintaan', 'error');
      btn.innerHTML = originalText;
      btn.disabled = false;
    });
  };

  window.populateDetailModal = function(data) {
    document.getElementById('detailKode').textContent = `REQ-${String(data.id).padStart(4, '0')}`;
    
    const statusBadge = document.getElementById('detailStatusBadge');
    const statusConfig = {
      'pending': { text: 'Menunggu Persetujuan', icon: 'fas fa-clock', color: '#f59e0b', bg: 'rgba(245,158,11,0.15)' },
      'dalam_review': { text: 'Dalam Review Kasubag', icon: 'fas fa-eye', color: '#f59e0b', bg: 'rgba(245,158,11,0.15)' },
      'disetujui': { text: 'Disetujui Admin', icon: 'fas fa-check-circle', color: '#10b981', bg: 'rgba(16,185,129,0.15)' },
      'disetujui_kasubag': { text: 'Disetujui Kasubag', icon: 'fas fa-thumbs-up', color: '#10b981', bg: 'rgba(16,185,129,0.15)' },
      'rejected': { text: 'Ditolak', icon: 'fas fa-times-circle', color: '#ef4444', bg: 'rgba(239,68,68,0.15)' },
      'cancelled': { text: 'Dibatalkan', icon: 'fas fa-ban', color: '#64748b', bg: 'rgba(148,163,184,0.15)' },
      'selesai': { text: 'Selesai', icon: 'fas fa-check-double', color: '#10b981', bg: 'rgba(16,185,129,0.15)' }
    };
    
    const status = statusConfig[data.status] || statusConfig['pending'];
    statusBadge.innerHTML = `<i class="${status.icon}"></i> ${status.text}`;
    statusBadge.style.background = status.bg;
    statusBadge.style.color = status.color;
    statusBadge.style.borderColor = status.color + '20';
    
    const fallbackName = "{!! auth()->user()->nama_lengkap ?? auth()->user()->name ?? '-' !!}";
    const fallbackNip = "{!! auth()->user()->nip ?? auth()->user()->nik ?? '-' !!}";
    
    const userName = data.user?.nama_lengkap || data.user?.name || data.pegawai?.nama_lengkap || data.pegawai?.name || data.nama_lengkap || fallbackName;
    const userNik = data.user?.nip || data.user?.nik || data.pegawai?.nip || data.pegawai?.nik || data.nip || data.nik || fallbackNip;

    document.getElementById('detailNama').textContent = userName;
    document.getElementById('detailNipNik').textContent = userNik;
    
    document.getElementById('detailFasilitas').textContent = data.persediaan?.nama_barang || data.nama_barang || '-';
    document.getElementById('detailKodeBarang').textContent = data.kode_barang || '-';
    document.getElementById('detailKategori').textContent = data.persediaan?.kategori || 'Umum';
    document.getElementById('detailJumlah').textContent = data.jumlah_diminta + ' ' + (data.satuan || 'Unit');
    
    document.getElementById('detailTglPermintaan').textContent = data.tanggal_permintaan ? new Date(data.tanggal_permintaan).toLocaleDateString('id-ID') : '-';
    
    document.getElementById('detailTujuan').textContent = data.tujuan_penggunaan || '-';
    
    const reviewedWrap = document.getElementById('detailReviewedWrap');
    const approvedWrap = document.getElementById('detailApprovedWrap');
    const komentarWrap = document.getElementById('detailKomentarWrap');
    
    reviewedWrap.style.display = 'none';
    approvedWrap.style.display = 'none';
    komentarWrap.style.display = 'none';
    
    if (data.admin_approved_by) {
      document.getElementById('detailReviewedBy').textContent = data.admin_approved_by;
      reviewedWrap.style.display = 'block';
    }
    if (data.kasubag_approved_by) {
      document.getElementById('detailApprovedBy').textContent = data.kasubag_approved_by;
      approvedWrap.style.display = 'block';
    }
    if (data.komentar_admin) {
      document.getElementById('detailKomentar').textContent = data.komentar_admin;
      komentarWrap.style.display = 'block';
    }
    
    document.getElementById('detailCreatedAt').textContent = data.created_at ? new Date(data.created_at).toLocaleDateString('id-ID', {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    }) : '-';
    
    const suratLink = document.getElementById('detailSuratLink');
    if (data.surat_bast_path) {
      suratLink.href = '/storage/' + data.surat_bast_path;
      suratLink.style.display = 'flex';
      suratLink.innerHTML = '<i class="fas fa-file-contract"></i> Lihat Surat BAST';
    } else {
      suratLink.style.display = 'none';
    }
    
    setTimeout(() => {
      const loadingElement = document.getElementById('detailLoading');
      const contentElement = document.getElementById('detailContent');
      if (loadingElement) loadingElement.style.display = 'none';
      if (contentElement) contentElement.style.display = 'block';
    }, 300);
  };

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  document.getElementById('detailModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'detailModal') closeModal();
  });
});

function showToast(msg, type = 'success') {
  const toast = document.getElementById('toast');
  const icon = document.getElementById('toastIcon');
  const msgEl = document.getElementById('toastMsg');
  if (!toast || !icon || !msgEl) return;
  msgEl.textContent = msg;
  icon.className = type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-circle-check';
  icon.style.color = type === 'error' ? '#ef4444' : '#10b981';
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 4000);
}
</script>
</body>
</html>