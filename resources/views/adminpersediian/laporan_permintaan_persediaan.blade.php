<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIPANDU - Laporan Permintaan</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --blue: #4F6FFF; --blue-dark: #3B5BDB;
    --green: #10B981; --red: #EF4444;
    --purple: #8B5CF6; --amber: #F59E0B;
    --teal: #14B8A6; --orange: #F97316;
    --sidebar-w: 240px; --radius: 16px;
    --bg: #F4F6FB; --surface: #FFFFFF;
    --text: #1E293B; --muted: #94A3B8; --border: #E8EDF5;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); display: flex; min-height: 100vh; }

  /* CSS SIDEBAR DAN TOPBAR BAWAAN KAMU */
  .sidebar { width: var(--sidebar-w); background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; }
  .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
  .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 28px; height: 56px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
  .topbar-title { font-size: 16px; font-weight: 700; }
  .topbar-right { display: flex; align-items: center; gap: 16px; }
  .notif-btn { width: 36px; height: 36px; border-radius: 50%; border: 1px solid var(--border); background: var(--surface); display: flex; align-items: center; justify-content: center; cursor: pointer; position: relative; }
  .notif-dot { width: 8px; height: 8px; background: #EF4444; border-radius: 50%; position: absolute; top: 6px; right: 6px; border: 2px solid white; }
  .date-text { font-size: 13px; color: #64748B; font-weight: 500; }
  .btn-keluar { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface); color: #64748B; font-size: 13px; font-weight: 600; cursor: pointer; }
  
  .content { padding: 28px; flex: 1; }
  .page-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
  .page-top h1 { font-size: 22px; font-weight: 800; color: var(--blue); margin-bottom: 4px; }
  .page-top p { font-size: 13px; color: var(--muted); }
  .btn-unduh { display: flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 10px; background: var(--blue); color: white; font-size: 13.5px; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(79,111,255,.35); transition: all .2s; }

  /* STATS */
  .stats-grid-3 { display: grid; grid-template-columns: repeat(3,1fr); gap: 16px; margin-bottom: 20px; }
  .stat-card { background: var(--surface); border-radius: var(--radius); padding: 20px; border: 1px solid var(--border); transition: transform .2s, box-shadow .2s; }
  .stat-header { display: flex; align-items: center; gap: 8px; margin-bottom: 12px; }
  .stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; }
  .stat-icon svg { width: 17px; height: 17px; }
  .stat-label-sm { font-size: 12.5px; font-weight: 600; color: #64748B; line-height: 1.3; }
  .stat-value { font-size: 30px; font-weight: 800; margin-bottom: 4px; }
  .stat-sub { font-size: 12px; color: var(--muted); }
  
  .ic-blue { background: #EFF6FF; } .ic-blue svg { fill: var(--blue); }
  .ic-green { background: #ECFDF5; } .ic-green svg { fill: var(--green); }
  .ic-red { background: #FEF2F2; } .ic-red svg { fill: var(--red); }
  .ic-teal { background: #F0FDFA; } .ic-teal svg { fill: var(--teal); }
  .ic-orange { background: #FFF7ED; } .ic-orange svg { fill: var(--orange); }

  /* CHARTS */
  .charts-row { display: grid; grid-template-columns: 1fr 320px; gap: 16px; margin-bottom: 20px; }
  .chart-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); padding: 22px; }
  .chart-title { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
  .chart-sub { font-size: 12px; color: var(--muted); margin-bottom: 18px; }
  .bar-chart { display: flex; align-items: flex-end; gap: 8px; height: 150px; }
  .bar-col { display: flex; flex-direction: column; align-items: center; flex: 1; gap: 4px; height: 100%; justify-content: flex-end; }
  .bar-wrap { flex: 1; display: flex; align-items: flex-end; width: 100%; height: 100%; }
  .bar { width: 100%; border-radius: 5px 5px 0 0; min-height: 4px; background: linear-gradient(180deg, var(--blue), var(--blue-dark)); transition: opacity .2s; }
  .bar-val { font-size: 9.5px; font-weight: 700; color: var(--text); }
  .bar-lbl { font-size: 9.5px; color: var(--muted); }

  /* Donut */
  .donut-container { position: relative; width: 150px; height: 150px; margin: 0 auto 14px; }
  .donut-svg { width: 100%; height: 100%; transform: rotate(-90deg); }
  .donut-text-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
  .donut-row { display: flex; align-items: center; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid var(--border); font-size: 12.5px; }
  .donut-dot { width: 10px; height: 10px; border-radius: 50%; margin-right: 8px; }

  /* TABS & TABLES */
  .tab-card { background: var(--surface); border-radius: var(--radius); border: 1px solid var(--border); overflow: hidden; }
  .tab-bar { display: flex; border-bottom: 1px solid var(--border); padding: 0 20px; }
  .tab-btn { padding: 14px 4px; font-size: 13.5px; font-weight: 600; color: var(--muted); border: none; background: none; cursor: pointer; border-bottom: 2.5px solid transparent; margin-right: 20px; }
  .tab-btn.active { color: var(--blue); border-bottom-color: var(--blue); }
  
  table { width: 100%; border-collapse: collapse; }
  thead tr { background: #F8FAFF; }
  th { padding: 12px 18px; text-align: left; font-size: 11px; font-weight: 700; color: var(--blue); text-transform: uppercase; border-bottom: 1px solid var(--border); }
  td { padding: 13px 18px; font-size: 13px; color: var(--text); border-bottom: 1px solid var(--border); vertical-align: middle; }
  
  .id-badge { display: inline-block; padding: 3px 9px; border-radius: 7px; background: #EEF2FF; color: var(--blue); font-size: 11.5px; font-weight: 700; }
  .status-badge { display: inline-block; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: capitalize;}
  
  .tab-panel { display: none; }
  .tab-panel.active { display: block; }
  .progress-bar { background: #F1F5F9; border-radius: 20px; height: 8px; margin-top: 10px; overflow: hidden; }
  .progress-fill { height: 100%; border-radius: 20px; }
</style>
</head>
<body>

@include('partials.sidebar')

<main class="main">
  <div class="topbar">
    <span class="topbar-title">Laporan Permintaan Persediaan</span>
    <div class="topbar-right">
      <span class="date-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, DD MMMM YYYY') }}</span>
      <form method="POST" action="{{ route('logout', [], false) }}">
          @csrf
          <button type="submit" class="btn-keluar">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zm-5 11H5V5h7V3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h7v-2z"/></svg>
            Keluar
          </button>
      </form>
    </div>
  </div>

  <div class="content">
    <div class="page-top">
      <div>
        <h1>Laporan Permintaan Persediaan</h1>
        <p>Analisis Data Persediaan dan Peminjaman</p>
      </div>
      <a href="{{ route('adminpersediaan.laporan.download', request()->query()) }}" class="btn-unduh" style="text-decoration: none;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="white"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
        Unduh Laporan (PDF)
      </a>
    </div>

    <!-- ROW 1: STATISTIK UMUM -->
    <div class="stats-grid-3">
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Total Permintaan</span>
        </div>
        <div class="stat-value" style="color:var(--blue)">{{ $stats['total'] }}</div>
        <div class="stat-sub">Seluruh riwayat transaksi</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-green">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/></svg>
          </div>
          <span class="stat-label-sm">Diproses / Pending</span>
        </div>
        <div class="stat-value" style="color:var(--amber)">{{ $stats['diproses'] }}</div>
        <div class="stat-sub">Menunggu persetujuan Anda</div>
      </div>

      <div class="stat-card">
        <div class="stat-header">
          <div class="stat-icon ic-red">
            <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
          </div>
          <span class="stat-label-sm">Ditolak</span>
        </div>
        <div class="stat-value" style="color:var(--red)">{{ $stats['ditolak'] }}</div>
        <div class="stat-sub">Permintaan dibatalkan/ditolak</div>
      </div>
    </div>

    <!-- ROW 2: STATISTIK BULAN INI -->
    <div class="stats-grid-3">
      <div class="stat-card" style="background:linear-gradient(135deg,#EFF6FF,#F5F8FF)">
        <div class="stat-header">
          <div class="stat-icon ic-blue">
            <svg viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
          </div>
          <span class="stat-label-sm">Status Bulan Ini</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--blue);margin-bottom:12px">
            {{ $statsBulanIni['total'] }} <span style="font-size:14px;color:var(--muted);font-weight:500">transaksi</span>
        </div>
        <div style="display:flex;gap:14px">
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--green)">{{ $statsBulanIni['disetujui'] }}</div>
            <div style="font-size:11px;color:#64748B">Disetujui</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--amber)">{{ $statsBulanIni['pending'] }}</div>
            <div style="font-size:11px;color:#64748B">Pending</div>
          </div>
          <div style="text-align:center">
            <div style="font-size:20px;font-weight:800;color:var(--red)">{{ $statsBulanIni['ditolak'] }}</div>
            <div style="font-size:11px;color:#64748B">Ditolak</div>
          </div>
        </div>
      </div>

      <div class="stat-card" style="background:linear-gradient(135deg,#ECFDF5,#F5FFF9)">
        <div class="stat-header">
          <div class="stat-icon ic-teal">
            <svg viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
          </div>
          <span class="stat-label-sm">Tingkat Persetujuan</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--teal);margin-bottom:8px">
            {{ $approvalRate }}% <span style="font-size:13px;color:var(--muted);font-weight:500">disetujui</span>
        </div>
        <div class="progress-bar">
          <div class="progress-fill" style="background:var(--teal); width: {{ $approvalRate }}%"></div>
        </div>
        <div style="font-size:11.5px;color:#64748B;margin-top:8px">Dari total seluruh peminjaman</div>
      </div>

      <div class="stat-card" style="background:linear-gradient(135deg,#FFF7ED,#FFFBF5)">
        <div class="stat-header">
          <div class="stat-icon ic-orange">
            <svg viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
          </div>
          <span class="stat-label-sm">Rata-rata Permintaan</span>
        </div>
        <div style="font-size:22px;font-weight:800;color:var(--orange);margin-bottom:8px">
            {{ $avgItems }} <span style="font-size:13px;color:var(--muted);font-weight:500">Unit</span>
        </div>
        <div style="font-size:11.5px;color:#64748B">Rata-rata jumlah barang per transaksi</div>
      </div>
    </div>

    <!-- CHARTS -->
    <div style="margin-bottom: 20px; max-width: 420px;">
      <div class="chart-card">
        <div class="chart-title">Distribusi Status</div>
        <div class="chart-sub">Komposisi bulan ini</div>
        
        <div class="donut-container">
            <svg class="donut-svg" viewBox="0 0 150 150">
              <circle cx="75" cy="75" r="55" fill="none" stroke="#F1F5F9" stroke-width="26"/>
              <!-- Lingkaran warna akan digambar oleh Javascript -->
              <circle id="arc-disetujui" cx="75" cy="75" r="55" fill="none" stroke="#10B981" stroke-width="26" stroke-dasharray="0 345.5" stroke-linecap="butt"/>
              <circle id="arc-pending" cx="75" cy="75" r="55" fill="none" stroke="#F59E0B" stroke-width="26" stroke-dasharray="0 345.5" stroke-linecap="butt"/>
              <circle id="arc-ditolak" cx="75" cy="75" r="55" fill="none" stroke="#EF4444" stroke-width="26" stroke-dasharray="0 345.5" stroke-linecap="butt"/>
            </svg>
            <div class="donut-text-center">
                <div style="font-size:20px; font-weight:800; color:#1E293B;">{{ $statsBulanIni['total'] }}</div>
                <div style="font-size:10px; color:#94A3B8;">transaksi</div>
            </div>
        </div>

        <div class="donut-labels">
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#10B981"></div>Disetujui</div>
            <div class="donut-pct" style="color:#10B981">{{ $statsBulanIni['disetujui'] }}</div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#F59E0B"></div>Pending</div>
            <div class="donut-pct" style="color:#F59E0B">{{ $statsBulanIni['pending'] }}</div>
          </div>
          <div class="donut-row">
            <div class="donut-name"><div class="donut-dot" style="background:#EF4444"></div>Ditolak</div>
            <div class="donut-pct" style="color:#EF4444">{{ $statsBulanIni['ditolak'] }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB TABS -->
    <div class="tab-card">
      <div class="tab-bar">
        <button class="tab-btn active" data-tab="pinjam">Detail Permintaan</button>
        <button class="tab-btn" data-tab="gabungan">Rekapitulasi per Pemohon</button>
      </div>

      <!-- Tab Permintaan Lengkap -->
      <div class="tab-panel active" id="tab-pinjam">
        <div style="overflow-x: auto;">
            <table>
            <thead>
                <tr>
                <th>ID</th><th>Pemohon</th><th>Item Diminta</th><th>Tanggal</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permintaan as $item)
                <tr>
                <td><span class="id-badge">REQ-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                <td><strong>{{ $item->nama_lengkap }}</strong></td>
                <td style="font-size:12.5px">{{ $item->persediaan->nama_barang ?? $item->nama_barang }} ({{ $item->jumlah_diminta }} Unit)</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_permintaan)->format('d M Y') }}</td>
                <td>
                    @php 
                        $statusColor = 'status-pending';
                        if(in_array($item->status, ['disetujui', 'disetujui_kasubag'])) $statusColor = 'status-disetujui';
                        if(in_array($item->status, ['ditolak', 'ditolak_kasubag'])) $statusColor = 'status-ditolak';
                    @endphp
                    <span class="status-badge {{ $statusColor }}">
                        {{ str_replace('_', ' ', $item->status) }}
                    </span>
                </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; padding:20px;">Belum ada data permintaan</td></tr>
                @endforelse
            </tbody>
            </table>
        </div>
      </div>

      <!-- Tab Rekapitulasi -->
      <div class="tab-panel" id="tab-gabungan">
        <div style="overflow-x: auto;">
            <table>
            <thead>
                <tr><th>Nama Pemohon</th><th>Total Transaksi</th><th>Disetujui</th><th>Pending</th><th>Ditolak</th></tr>
            </thead>
            <tbody>
                @foreach($summaryData as $nama => $data)
                <tr>
                <td><strong>{{ $nama }}</strong></td>
                <td>{{ $data['total'] }}</td>
                <td><span style="color:var(--green);font-weight:700">{{ $data['disetujui'] }}</span></td>
                <td><span style="color:var(--amber);font-weight:700">{{ $data['pending'] }}</span></td>
                <td><span style="color:var(--red);font-weight:700">{{ $data['ditolak'] }}</span></td>
                </tr>
                @endforeach
                <tr style="background:#F8FAFF">
                <td><strong>TOTAL KESELURUHAN</strong></td>
                <td><strong>{{ $stats['total'] }}</strong></td>
                <td style="color:var(--green);font-weight:700">{{ $stats['disetujui'] }}</td>
                <td style="color:var(--amber);font-weight:700">{{ $stats['diproses'] }}</td>
                <td style="color:var(--red);font-weight:700">{{ $stats['ditolak'] }}</td>
                </tr>
            </tbody>
            </table>
        </div>
      </div>

    </div>
  </div>
</main>

<script>
  // Logika untuk Tab
  document.querySelectorAll('.tab-btn[data-tab]').forEach(btn => {
    btn.addEventListener('click', function() {
      document.querySelectorAll('.tab-btn[data-tab]').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      this.classList.add('active');
      document.getElementById('tab-' + this.dataset.tab).classList.add('active');
    });
  });

  // Logika untuk menggambar Grafik Donut Berdasarkan Data
  window.onload = function() {
      const total = {{ $statsBulanIni['total'] > 0 ? $statsBulanIni['total'] : 1 }};
      const disetujui = {{ $statsBulanIni['disetujui'] }};
      const pending = {{ $statsBulanIni['pending'] }};
      const ditolak = {{ $statsBulanIni['ditolak'] }};
      
      const circumference = 345.5; // Keliling lingkaran SVG (2 * pi * r) untuk r=55

      const pctDisetujui = (disetujui / total) * circumference;
      const pctPending = (pending / total) * circumference;
      const pctDitolak = (ditolak / total) * circumference;

      // Menata batas dari masing-masing garis
      document.getElementById('arc-disetujui').style.strokeDasharray = `${pctDisetujui} ${circumference}`;
      
      document.getElementById('arc-pending').style.strokeDasharray = `${pctPending} ${circumference}`;
      document.getElementById('arc-pending').style.strokeDashoffset = `-${pctDisetujui}`;
      
      document.getElementById('arc-ditolak').style.strokeDasharray = `${pctDitolak} ${circumference}`;
      document.getElementById('arc-ditolak').style.strokeDashoffset = `-${pctDisetujui + pctPending}`;
  }
</script>

</body>
</html>
