<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SIPANDU - Kelola Pengguna</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --sidebar-w: 240px;
      --primary: #3b5bdb;
      --primary-light: #eef2ff;
      --sidebar-bg: #1e2a4a;
      --text: #1a1a2e;
      --muted: #6b7280;
      --border: #e5e7eb;
      --card-bg: #ffffff;
      --bg: #f4f6fb;
      --danger: #ef4444;
      --success: #10b981;
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }
    .main { margin-left: 260px; flex: 1; display: flex; flex-direction: column; }
    .topbar { background: #fff; padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 50; }
    .page-title { font-size: 20px; font-weight: 700; }
    .page-sub { font-size: 13px; color: var(--muted); margin-top: 2px; }
    .topbar-right { display: flex; align-items: center; gap: 12px; }
    .avatar-top { width: 38px; height: 38px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 14px; cursor: pointer; }
    .content { padding: 28px 32px; flex: 1; }

    /* ALERTS */
    .alert { padding: 12px 20px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

    /* TOOLBAR & SEARCH */
    .table-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
    .search-box { display: flex; align-items: center; background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: 8px 16px; width: 300px; }
    .search-box input { border: none; outline: none; width: 100%; font-family: inherit; font-size: 13px; margin-left: 8px; }
    
    .toolbar-actions { display: flex; gap: 10px; }
    .btn-log { background: #fff; border: 1px solid var(--border); border-radius: 8px; padding: 10px 18px; font-size: 14px; font-weight: 600; color: var(--text); cursor: pointer; display: flex; align-items: center; gap: 8px; transition: .2s; }
    .btn-log:hover { background: #f9fafb; border-color: var(--primary); color: var(--primary); }
    .btn-add { display: flex; align-items: center; gap: 8px; background: var(--primary); color: #fff; border: none; border-radius: 8px; padding: 10px 18px; font-size: 14px; font-weight: 600; cursor: pointer; transition: .2s; }
    .btn-add:hover { background: #2f4ac7; }

    /* TABLE */
    .table-card { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); overflow: hidden; }
    .table-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid var(--border); background: #fff; }
    .user-count { font-size: 14px; color: var(--muted); font-weight: 500; }
    table { width: 100%; border-collapse: collapse; }
    thead th { text-align: left; padding: 12px 24px; font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; background: #f9fafb; border-bottom: 1px solid var(--border); }
    tbody tr { transition: background .15s; }
    tbody tr:hover { background: #f9fafb; }
    tbody td { padding: 14px 24px; font-size: 13px; border-bottom: 1px solid var(--border); vertical-align: middle; }
    .td-name { font-weight: 600; color: var(--text); font-size: 14px; }
    .td-sub { font-size: 12px; color: var(--muted); margin-top: 2px;}
    
    /* ROLE BADGES */
    .role-badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: capitalize; }
    .role-superadmin { background: #eef2ff; color: #3b5bdb; }
    .role-kepalabpmp { background: #ebfbee; color: #2f9e44; }
    .role-kasubag { background: #fff3bf; color: #e67700; }
    .role-adminpersediaan { background: #e3fafc; color: #0c8599; }
    .role-adminsarpras { background: #f3f0ff; color: #6741d9; }
    .role-adminasettetap { background: #fff0f6; color: #c2255c; }
    .role-pegawai { background: #f3f4f6; color: #495057; }
    .role-tamu { background: #fff9db; color: #a06a00; }

    /* STATUS TOGGLE */
    .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 11px; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
    
    /* ACTIONS */
    .action-group { display: flex; gap: 6px; align-items: center; }
    .btn-action { background: none; border: 1px solid var(--border); border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; cursor: pointer; transition: .2s; display: inline-flex; align-items: center; gap: 4px;}
    .btn-action.edit { color: var(--primary); }
    .btn-action.edit:hover { background: var(--primary-light); border-color: var(--primary); }
    .btn-action.delete { color: var(--danger); }
    .btn-action.delete:hover { background: #fee2e2; border-color: var(--danger); }
    .btn-action.toggle { color: #4b5563; }
    .btn-action.toggle:hover { background: #f3f4f6; border-color: #9ca3af; }

    /* MODAL */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.35); z-index: 200; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-overlay.open { display: flex; }
    .modal { background: #fff; border-radius: 16px; padding: 24px 32px; width: 500px; max-width: 95vw; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.15); animation: slideUp .25s ease; }
    .modal.large { width: 700px; }
    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .modal-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;}
    .modal-title i { cursor: pointer; color: var(--muted); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text); }
    .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 13px; outline: none; transition: .2s; }
    .form-group input:focus, .form-group select:focus { border-color: var(--primary); }
    .help-text { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; }
    .modal-actions { display: flex; gap: 10px; margin-top: 24px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--border); }
    .btn-save { padding: 10px 20px; border: none; border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600; cursor: pointer; }

    /* LOG TABLE */
    .log-table th, .log-table td { padding: 10px 12px; font-size: 12px; }
    .log-device { font-size: 10px; color: var(--muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; }

    @media (max-width: 768px) {
      .main { margin-left: 0; }
      .topbar { padding: 16px; padding-top: 70px; }
      .content { padding: 16px; }
      .search-box { width: 100%; }
      .table-toolbar { flex-direction: column; }
      .toolbar-actions { width: 100%; justify-content: stretch; }
      .toolbar-actions .btn-log, .toolbar-actions .btn-add { flex: 1; justify-content: center; }
      .table-card { overflow-x: auto; }
      table { min-width: 600px; }
      .form-row { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

@include('partials.sidebar')

<div class="main">
  <div class="topbar">
    <div>
      <div class="page-title">Manajemen User & Hak Akses</div>
      <div class="page-sub">Operator — BPMP Provinsi Gorontalo</div>
    </div>
    <div class="topbar-right">
      <div class="avatar-top">{{ substr(Auth::user()->name ?? 'SA', 0, 2) }}</div>
    </div>
  </div>

  <div class="content">
    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error') || $errors->any())
      <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') ?? 'Terdapat kesalahan pada input form Anda.' }}</div>
    @endif

    <div class="table-toolbar">
      <form action="{{ url()->current() }}" method="GET" class="search-box">
        <i class="fas fa-search" style="color: var(--muted)"></i>
        <input type="text" name="search" placeholder="Cari nama, NIP, username..." value="{{ request('search') }}">
      </form>
      
      <div class="toolbar-actions">
        <!-- Tombol Buka Modal Log Aktivitas -->
        <button class="btn-log" onclick="openLogModal()">
          <i class="fas fa-history"></i> Log Aktivitas
        </button>
        <button class="btn-add" onclick="openAddModal()">
          <i class="fas fa-user-plus"></i> Tambah User
        </button>
      </div>
    </div>

    <div class="table-card">
      <div class="table-header">
        <span class="user-count">Total {{ $users->total() }} pengguna terdaftar dalam sistem</span>
      </div>

      <table>
        <thead>
          <tr>
            <th>Pegawai / User</th>
            <th>Role Akses</th>
            <th>Username</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $index => $user)
          <tr>
            <td>
              <div class="td-name">{{ $user->name }}</div>
              <div class="td-sub">{{ $user->jabatan ?? 'Belum ada jabatan' }} | NIP: {{ $user->nip ?? '-' }}</div>
            </td>
            <td>
              <span class="role-badge role-{{ $user->role }}">
                {{ match($user->role) {
                  'superadmin' => 'Operator', 'kepalabpmp' => 'Kepala BPMP', 'kasubag' => 'Kasubag Umum',
                  'adminpersediaan' => 'Admin Persediaan', 'adminsarpras' => 'Admin Sarpras',
                  'adminasettetap' => 'Admin Aset Tetap', 'pegawai' => 'Pegawai', 'tamu' => 'Tamu',
                  default => $user->role
                } }}
              </span>
            </td>
            <td class="td-user">{{ $user->username }}</td>
            <td>
              @if($user->is_active)
                <span class="status-badge status-active"><i class="fas fa-check"></i> Aktif</span>
              @else
                <span class="status-badge status-inactive"><i class="fas fa-lock"></i> Nonaktif</span>
              @endif
            </td>
            <td>
              <div class="action-group">
                <button class="btn-action edit" onclick="openEditModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}', '{{ $user->nip }}', '{{ $user->jabatan }}')">
                  <i class="fas fa-edit"></i>
                </button>
                
                @if(auth()->id() !== $user->id)
                  <!-- Form Toggle Status (Aktif/Nonaktif) -->
                  <form action="{{ route('superadmin.pengguna.toggle-status', $user->id) }}" method="POST" style="margin:0;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-action toggle" title="{{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}">
                      <i class="fas {{ $user->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                    </button>
                  </form>

                  <!-- Form Hapus -->
                  <form action="{{ route('superadmin.pengguna.destroy', $user->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Yakin hapus user ini? Data tidak dapat dikembalikan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action delete"><i class="fas fa-trash"></i></button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align: center; padding: 40px; color: var(--muted);">Tidak ada data user.</td></tr>
          @endforelse
        </tbody>
      </table>
      
      <div style="padding: 16px 24px;">{{ $users->links() }}</div>
    </div>
  </div>
</div>

<!-- MODAL TAMBAH / EDIT USER -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal">
    <div class="modal-title">
      <span id="modalTitle">Tambah User Baru</span>
      <i class="fas fa-times" onclick="closeModal('modalOverlay')"></i>
    </div>
    <form id="userForm" action="{{ route('superadmin.pengguna.store') }}" method="POST">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      
      <div class="form-group">
        <label>Nama Lengkap <span style="color:red">*</span></label>
        <input type="text" name="name" id="fieldName" required />
      </div>
      
      <div class="form-row">
        <div class="form-group">
          <label>NIP</label>
          <input type="text" name="nip" id="fieldNip" />
        </div>
        <div class="form-group">
          <label>Username <span style="color:red">*</span></label>
          <input type="text" name="username" id="fieldUsername" required />
        </div>
      </div>

      <div class="form-group">
        <label>Jabatan</label>
        <input type="text" name="jabatan" id="fieldJabatan" />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" id="fieldEmail" />
        </div>
        <div class="form-group">
          <label>Role Akses <span style="color:red">*</span></label>
          <select name="role" id="fieldRole" required>
            <option value="">-- Pilih Role --</option>
            <option value="superadmin">Operator</option>
            <option value="kepalabpmp">Kepala BPMP</option>
            <option value="kasubag">Kasubag TU</option>
            <option value="adminpersediaan">Admin Persediaan</option>
            <option value="adminsarpras">Admin Sarana Prasarana</option>
            <option value="adminasettetap">Admin Aset Tetap</option>
            <option value="pegawai">Pegawai</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Password <span id="passwordReqSpan" style="color:red">*</span></label>
        <input type="password" name="password" id="fieldPassword" />
        <span class="help-text" id="passwordHelpText" style="display:none;">Kosongkan jika tidak mengubah password.</span>
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn-save">Simpan Data</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL LOG AKTIVITAS -->
<div class="modal-overlay" id="modalLog">
  <div class="modal large">
    <div class="modal-title">
      <span><i class="fas fa-history" style="color:var(--primary)"></i> Riwayat Login & Aktivitas Terakhir</span>
      <i class="fas fa-times" onclick="closeModal('modalLog')"></i>
    </div>
    
    <div style="border: 1px solid var(--border); border-radius: 8px; overflow:hidden;">
      <table class="log-table">
        <thead>
          <tr>
            <th>User</th>
            <th>Role</th>
            <th>Terakhir Online</th>
            <th>IP Address</th>
            <th>Perangkat</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logAktivitas as $log)
          <tr>
            <td style="font-weight:600">{{ $log->name }}</td>
            <td><span class="role-badge role-{{ $log->role }}">{{ $log->role }}</span></td>
            <td style="color:var(--success); font-weight:600;"><i class="fas fa-circle" style="font-size:8px;"></i> {{ $log->last_activity_format }}</td>
            <td style="font-family:monospace">{{ $log->ip_address }}</td>
            <td><span class="log-device" title="{{ $log->user_agent }}">{{ $log->user_agent }}</span></td>
          </tr>
          @empty
          <tr><td colspan="5" style="text-align:center;">Belum ada data aktivitas terekam.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const storeUrl = "{{ route('superadmin.pengguna.store') }}";
  const updateUrlBase = "{{ url('superadmin/pengguna') }}"; 

  function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Tambah User Baru';
    document.getElementById('userForm').action = storeUrl;
    document.getElementById('formMethod').value = 'POST';
    
    document.getElementById('fieldName').value = '';
    document.getElementById('fieldUsername').value = '';
    document.getElementById('fieldNip').value = '';
    document.getElementById('fieldJabatan').value = '';
    document.getElementById('fieldEmail').value = '';
    document.getElementById('fieldRole').value = '';
    
    const passField = document.getElementById('fieldPassword');
    passField.value = '';
    passField.required = true;
    document.getElementById('passwordReqSpan').style.display = 'inline';
    document.getElementById('passwordHelpText').style.display = 'none';

    document.getElementById('modalOverlay').classList.add('open');
  }

  function openEditModal(id, name, username, email, role, nip, jabatan) {
    document.getElementById('modalTitle').textContent = 'Edit Data User';
    document.getElementById('userForm').action = updateUrlBase + '/' + id;
    document.getElementById('formMethod').value = 'PUT';
    
    document.getElementById('fieldName').value = name;
    document.getElementById('fieldUsername').value = username;
    document.getElementById('fieldNip').value = (nip !== '-' && nip !== 'null') ? nip : '';
    document.getElementById('fieldJabatan').value = (jabatan !== '-' && jabatan !== 'null') ? jabatan : '';
    document.getElementById('fieldEmail').value = (email !== '-' && email !== 'null') ? email : '';
    document.getElementById('fieldRole').value = role;
    
    const passField = document.getElementById('fieldPassword');
    passField.value = '';
    passField.required = false;
    document.getElementById('passwordReqSpan').style.display = 'none';
    document.getElementById('passwordHelpText').style.display = 'block';

    document.getElementById('modalOverlay').classList.add('open');
  }

  function openLogModal() {
    document.getElementById('modalLog').classList.add('open');
  }

  function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('open');
  }

  // Tutup modal jika klik di luar area konten
  window.onclick = function(event) {
    if (event.target.classList.contains('modal-overlay')) {
      event.target.classList.remove('open');
    }
  }
</script>

</body>
</html>