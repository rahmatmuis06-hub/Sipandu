<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>SIPANDU - BPMP Provinsi Gorontalo</title>
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

    @keyframes fadeUp   { from { opacity:0; transform:translateY(30px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeIn   { from { opacity:0; } to { opacity:1; } }
    @keyframes floatBob { 0%,100%{ transform:translateY(0); } 50%{ transform:translateY(-12px); } }
    @keyframes modalIn  { from { opacity:0; transform:scale(0.96) translateY(16px); } to { opacity:1; transform:scale(1) translateY(0); } }
    @keyframes overlayIn{ from { opacity:0; } to { opacity:1; } }
    @keyframes imgZoom  { from { transform:scale(1.08); } to { transform:scale(1); } }

    .anim-fade-up { animation: fadeUp 0.7s ease forwards; }
    .anim-fade-in { animation: fadeIn 0.5s ease forwards; }
    .delay-1 { animation-delay:0.1s; opacity:0; }
    .delay-2 { animation-delay:0.2s; opacity:0; }
    .delay-3 { animation-delay:0.3s; opacity:0; }
    .delay-4 { animation-delay:0.4s; opacity:0; }
    .delay-5 { animation-delay:0.5s; opacity:0; }
    .delay-6 { animation-delay:0.6s; opacity:0; }

    .card-hover { transition: all 0.3s ease; }
    .card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(10,21,80,0.12); }
    .btn-primary { background: linear-gradient(135deg, #1a2f9b 0%, #3355ff 100%); transition: all 0.3s ease; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(26,47,155,0.35); }
    .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.15); }
    .stat-card { position: relative; overflow: hidden; }
    .stat-card::before { content:''; position:absolute; top:-50%; right:-50%; width:100%; height:100%; background:radial-gradient(circle, rgba(51,85,255,0.08) 0%, transparent 70%); }

    .facilities-scroll { -webkit-overflow-scrolling: touch; scrollbar-width: none; -ms-overflow-style: none; }
    .facilities-scroll::-webkit-scrollbar { display: none; }

    .facility-card { cursor: pointer; }
    .facility-card .card-img { transition: transform 0.4s ease; }
    .facility-card:hover .card-img { transform: scale(1.05); }
    .facility-card .card-overlay { opacity: 0; transition: opacity 0.3s ease; background: linear-gradient(to top, rgba(6,13,51,0.85) 0%, rgba(6,13,51,0.1) 60%, transparent 100%); }
    .facility-card:hover .card-overlay { opacity: 1; }
    .facility-card .view-detail-btn { opacity: 0; transform: translateY(8px); transition: all 0.3s ease; }
    .facility-card:hover .view-detail-btn { opacity: 1; transform: translateY(0); }

    .modal-overlay {
      position: fixed; inset: 0; z-index: 1000;
      background: rgba(6,13,51,0.72); backdrop-filter: blur(6px);
      display: flex; align-items: center; justify-content: center;
      padding: 16px;
      animation: overlayIn 0.25s ease forwards;
    }
    .modal-box {
      background: #fff; border-radius: 24px;
      width: 100%; max-width: 860px; max-height: 90vh;
      overflow: hidden; display: flex; flex-direction: column;
      animation: modalIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
      box-shadow: 0 32px 80px rgba(6,13,51,0.28);
    }
    .modal-body { overflow-y: auto; }
    .modal-body::-webkit-scrollbar { width: 5px; }
    .modal-body::-webkit-scrollbar-thumb { background: #bccfff; border-radius: 4px; }
    .modal-img { animation: imgZoom 0.5s ease forwards; }

    .thumb { cursor: pointer; transition: all 0.2s; border: 2px solid transparent; border-radius: 10px; overflow: hidden; }
    .thumb:hover { border-color: #3355ff; }
    .thumb.active { border-color: #3355ff; box-shadow: 0 0 0 3px rgba(51,85,255,0.2); }

    .hero-bg { background: linear-gradient(135deg, #060d33 0%, #0f1f6e 40%, #1a2f9b 70%, #3355ff 100%); }

    .carousel-modern {
            position: relative;
            width: 400px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .carousel-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 212, 255, 0.2);
        }

        .carousel-modern-inner {
            position: relative;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1) 0%, rgba(0, 255, 136, 0.1) 100%);
        }

        .carousel-modern-slide {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-modern-slide.active {
            opacity: 1;
        }

        .carousel-modern-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: zoomIn 0.7s ease-out;
        }

        @keyframes zoomIn {
            from { transform: scale(1.05); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .carousel-modern-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 20;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            padding: 8px 12px;
            border-radius: 20px;
        }

        .carousel-modern-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .carousel-modern-dot.active {
            background: #00d4ff;
            box-shadow: 0 0 12px #00d4ff;
            width: 30px;
            border-radius: 5px;
        }

        .carousel-modern-dot:hover:not(.active) {
            background: rgba(255, 255, 255, 0.6);
        }

        .carousel-modern-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 212, 255, 0.2);
            border: 1px solid rgba(0, 212, 255, 0.4);
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00d4ff;
            font-size: 18px;
            transition: all 0.3s ease;
            z-index: 15;
            backdrop-filter: blur(10px);
        }

        .carousel-modern-arrow:hover {
            background: rgba(0, 212, 255, 0.4);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }

    .carousel-modern-arrow.prev { left: 12px; }
    .carousel-modern-arrow.next { right: 12px; }

    #facilitiesCarouselScroll {
    height: 100% !important;
    align-items: center !important;
    }

    .facility-card {
      height: 90% !important;  /* Hampir full height carousel */
      min-height: 450px;
    }

    .facilities-scroll {
      scrollbar-width: thin;
      scrollbar-color: #bccfff transparent;
    }

    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #bccfff; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #8eaeff; }

    .avail-tersedia { background:#dcfce7; color:#15803d; }
    .avail-terbatas { background:#fef9c3; color:#a16207; }
    .avail-beberapa { background:#fee2e2; color:#b91c1c; }
    .avail-booking  { background:#ede9fe; color:#7c3aed; }
  </style>
</head>
<body class="h-full bg-slate-50">

  <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300" style="background:rgba(6,13,51,0.95);backdrop-filter:blur(12px);box-shadow:rgba(0,0,0,0.15) 0px 4px 20px;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-2 h-16 md:h-20">
        <div class="flex items-center gap-2">
          <img src="{{ asset('storage/logo_kemendikdasmen.png') }}" 
              alt="Logo Kemendikdasmen" 
              class="w-12 h-12 object-contain">

          <div class="leading-tight">
            <h1 class="text-lg font-bold">
              <span class="text-blue-400">Kemen</span><span class="text-yellow-400">dikdasmen</span>
            </h1>
            <p class="text-xs text-white">BPMP Gorontalo</p>
          </div>
        </div>

        <div class="hidden md:flex items-center gap-8">
          <a href="#hero"      class="text-blue-100 hover:text-white text-sm font-medium transition">Beranda</a>
          <a href="#fitur"     class="text-blue-100 hover:text-white text-sm font-medium transition">Fitur</a>
          <a href="#statistik" class="text-blue-100 hover:text-white text-sm font-medium transition">Statistik</a>
          <a href="#fasilitas" class="text-blue-100 hover:text-white text-sm font-medium transition">Fasilitas</a>
          {{-- <a href="#pengaduan-survey" class="text-blue-100 hover:text-white text-sm font-medium transition">Pengaduan</a> --}}
          <a href="#kontak"    class="text-blue-100 hover:text-white text-sm font-medium transition">Kontak</a>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('login') }}"    class="text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-white/10 transition">Masuk</a>
          <a href="{{ route('register') }}" class="btn-primary text-white text-sm font-semibold px-5 py-2 rounded-lg">Daftar</a>
          <button class="md:hidden text-white" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          </button>
        </div>
      </div>
    </div>
    <div id="mobileMenu" class="hidden md:hidden bg-navy-900/95 backdrop-blur-lg pb-4 px-4">
      <a href="#hero"      class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Beranda</a>
      <a href="#fitur"     class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Fitur</a>
      <a href="#statistik" class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Statistik</a>
      <a href="#fasilitas" class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Fasilitas</a>
      {{-- <a href="#pengaduan-survey" class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Pengaduan</a> --}}
      <a href="#kontak"    class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Kontak</a>
      <div class="flex gap-2 mt-3 pt-3 border-t border-white/10">
        <a href="{{ route('login') }}"    class="flex-1 text-center text-white text-sm font-semibold py-2 rounded-lg border border-white/20 hover:bg-white/10 transition">Masuk</a>
        <a href="{{ route('register') }}" class="flex-1 text-center btn-primary text-white text-sm font-semibold py-2 rounded-lg">Daftar</a>
      </div>
    </div>
  </nav>

  <section id="hero" class="hero-bg relative overflow-hidden" style="min-height:600px;padding-top:100px;padding-bottom:80px;">
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute top-20 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl"></div>
      <div class="absolute bottom-10 right-10 w-96 h-96 bg-indigo-400/8 rounded-full blur-3xl"></div>
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/5 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12">
      <div class="flex-1 text-center lg:text-left">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-6 anim-fade-up delay-1">
          <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
          <span class="text-blue-200 text-xs font-medium">SIPANDU</span>
        </div>
        <h1 class="text-3xl font-extrabold text-white leading-tight mb-6 anim-fade-up delay-2">
          Sistem Informasi Pendataan BMN Terpadu (SIPANDU)<br>
          <span class="bg-gradient-to-r from-blue-300 to-cyan-300 bg-clip-text text-transparent">BPMP GORONTALO</span>
        </h1>
        <p class="text-blue-200 text-base md:text-lg max-w-xl mb-8 leading-relaxed anim-fade-up delay-3">
          Pendataan dan monitoring BMN (Barang Milik Negara) pada Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo secara digital, transparan, dan akuntabel.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start anim-fade-up delay-4">
          <a href="{{ route('login') }}" class="btn-primary text-white font-semibold px-8 py-3.5 rounded-xl flex items-center justify-center gap-2 text-base">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
            Masuk Sekarang
          </a>
          <a href="#fitur" class="border border-white/30 text-white font-semibold px-8 py-3.5 rounded-xl flex items-center justify-center gap-2 text-base hover:bg-white/10 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            Pelajari Fitur
          </a>
        </div>
        <div class="flex items-center gap-8 mt-10 justify-center lg:justify-start anim-fade-up delay-5">
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ number_format($totalItemBMN) }}</div>
            <div class="text-blue-300 text-xs">Total Aset</div>
          </div>
          <div class="w-px h-10 bg-white/20"></div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ number_format($totalPegawai) }}</div>
            <div class="text-blue-300 text-xs">Pegawai</div>
          </div>
          <div class="w-px h-10 bg-white/20"></div>
          <div class="text-center">
            <div class="text-2xl font-bold text-white">{{ number_format($totalFasilitas) }}</div>
            <div class="text-blue-300 text-xs">Fasilitas</div>
          </div>
        </div>
      </div>

      <div class="flex-1 hidden lg:flex justify-center anim-fade-up delay-5">
        <div class="relative">
          <div class="w-[400px] h-[300px] bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 p-6">
             <div class="wrapper">
              <div class="carousel-grid">
                <div class="carousel-container">
                  <div class="carousel-modern" id="carousel1">
                      <div class="carousel-modern-inner">
                          <div class="carousel-modern-slide active">
                            <img src="{{ ('storage/fasilitas/kantor_utama.jpeg') }}" class="w-full h-full object-cover">
                          </div>
                          <div class="carousel-modern-slide">
                              <img src="{{ ('storage/fasilitas/kantor_ponuwa.jpeg') }}" class="w-full h-full object-cover">
                          </div>
                          <div class="carousel-modern-slide">
                              <img src="{{ ('storage/fasilitas/gedung_aula.jpeg') }}" class="w-full h-full object-cover">
                          </div>
                          <div class="carousel-modern-slide">
                              <img src="{{ ('storage/fasilitas/tinelo_1.jpeg') }}" class="w-full h-full object-cover">
                          </div>
                          </div>
                          <button class="carousel-modern-arrow prev" onclick="carouselPrev(this)">‹</button>
                          <button class="carousel-modern-arrow next" onclick="carouselNext(this)">›</button>
                          <div class="carousel-modern-nav" id="nav1"></div>
                      </div>
                  </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto text-center">
        <div class="inline-flex items-center gap-2 bg-blue-50 rounded-full px-4 py-1.5 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
          <span class="text-navy-700 text-sm font-semibold">Tentang SIPANDU</span>
        </div>
        <h2 class="text-3xl font-bold text-navy-900 mb-4">Pengelolaan BMN yang Modern &amp; Transparan</h2>
        <p class="text-slate-600 leading-relaxed">SIPANDU BPMP Gorontalo adalah sistem informasi terintegrasi untuk pencatatan, pemantauan, dan pengelolaan Barang Milik Negara. Sistem ini mendukung tata kelola aset yang akuntabel sesuai Peraturan Pemerintah No. 27 Tahun 2014 tentang Pengelolaan BMN, dengan fitur multi-peran untuk seluruh unit kerja di lingkungan BPMP Provinsi Gorontalo.</p>
      </div>
    </div>
  </section>

  <section id="fitur" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <div class="inline-flex items-center gap-2 bg-blue-50 rounded-full px-4 py-1.5 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
          <span class="text-navy-700 text-sm font-semibold">Fitur Unggulan</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-navy-900">Fitur Lengkap untuk Pengelolaan BMN</h2>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M16.5 9.4 7.55 4.24"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Pencatatan Persediaan</h3><p class="text-slate-500 text-sm leading-relaxed">Kelola stok barang habis pakai dan bahan operasional dengan pencatatan masuk (pengadaan)-keluar (pengahapusan) yang akurat.</p></div>
        {{-- <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Manajemen Sarana Prasarana</h3><p class="text-slate-500 text-sm leading-relaxed">Monitoring dan pengelolaan peminjaman/sewa fasilitas (Gedung)</p></div> --}}
        <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Pengelolaan Aset Tetap</h3><p class="text-slate-500 text-sm leading-relaxed">Inventarisas aset tetap pencatatan masuk (pengadaan)-keluar (pengahapusan) aset yang akurat</p></div>
        <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="m9 15 2 2 4-4"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Peminjaman &amp; Pengembalian</h3><p class="text-slate-500 text-sm leading-relaxed">Sistem peminjaman aset oleh pegawai dengan tracking status dan riwayat lengkap.</p></div>
        <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Laporan &amp; Analitik</h3><p class="text-slate-500 text-sm leading-relaxed">Generate laporan BMN otomatis, neraca aset, dan analisis kondisi barang secara real-time.</p></div>
        <div class="card-hover bg-white rounded-2xl p-7 border border-slate-100"><div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-4"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-rose-600"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><h3 class="font-bold text-navy-900 text-lg mb-2">Multi-Role Access</h3><p class="text-slate-500 text-sm leading-relaxed">Hak akses berbeda untuk Kepala BPMP, Kasubag, Admin Persediaan, Admin Sarpras, Admin Aset, dan Pegawai.</p></div>
      </div>
    </div>
  </section>

  <section id="statistik" class="py-20 hero-bg relative overflow-hidden">
    <div class="absolute inset-0"><div class="absolute top-0 right-0 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-14">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur rounded-full px-4 py-1.5 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-300"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          <span class="text-blue-200 text-sm font-semibold">Data Statistik</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-bold text-white">Statistik BMN BPMP Gorontalo</h2>
      </div>
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        
        <div class="stat-card glass rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-300"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            </div>
            <div class="text-3xl md:text-4xl font-extrabold text-white mb-1">{{ number_format($totalItemBMN) }}</div>
            <div class="text-blue-200 text-sm">Total Item BMN</div>
        </div>

        <div class="stat-card glass rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-300"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="text-3xl md:text-4xl font-extrabold text-white mb-1">Rp {{ $formattedNilaiAset }}M</div>
            <div class="text-blue-200 text-sm">Nilai Aset Terkelola</div>
        </div>

        <div class="stat-card glass rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-yellow-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-300"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
            </div>
            <div class="text-3xl md:text-4xl font-extrabold text-white mb-1">{{ $totalTransaksiBulanIni }}</div>
            <div class="text-blue-200 text-sm">Transaksi Bulan Ini</div>
        </div>

        <div class="stat-card glass rounded-2xl p-6 text-center">
            <div class="w-14 h-14 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-300"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="text-3xl md:text-4xl font-extrabold text-white mb-1">{{ $persentaseKondisiBaik }}%</div>
            <div class="text-blue-200 text-sm">Kondisi Baik</div>
        </div>

      </div>
    </div>
  </section>

  <section id="fasilitas" class="h-screen flex items-center bg-slate-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="px-5 pt-6 pb-4 md:px-8 md:pt-8 md:pb-5 border-b border-slate-100/80">
          <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5 mb-5">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-blue-100/80">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
              </div>
              <div>
                <h2 class="text-xl md:text-2xl font-bold text-navy-900 tracking-tight leading-tight">Fasilitas</h2>
                <p class="text-slate-500 text-sm mt-1">Klik gambar untuk melihat detail — BPMP Provinsi Gorontalo</p>
              </div>
            </div>
            <div class="flex items-center gap-2 lg:gap-3 flex-wrap lg:flex-nowrap lg:justify-end">
            </div>
          </div>
          <div id="facilityChips" class="flex flex-wrap gap-2"></div>
        </div>
        <div class="relative flex-1 px-3 pb-6 md:px-6 bg-slate-50/70 flex items-center">
          <button onclick="facilitiesScrollPrev()" class="absolute left-1 md:left-2 top-[42%] -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white shadow-lg hidden sm:flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-navy-700 transition border border-white/80">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <div id="facilitiesCarouselScroll" class="facilities-scroll flex gap-3 md:gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory py-2 pl-1 pr-14 sm:pr-16 md:px-2"></div>
          <button onclick="facilitiesScrollNext()" class="absolute right-1 md:right-2 top-[42%] -translate-y-1/2 z-10 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white shadow-lg flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-navy-700 transition border border-white/80">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
          </button>
        </div>
      </div>
    </div>
  </section>

  {{-- <section id="pengaduan-survey" class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <div class="grid lg:grid-cols-2 gap-12 mb-16">
        
        <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center border border-red-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0  0-3.42 0z"/>
                <line x1="12" x2="12" y1="9" y2="13"/>
                <line x1="12" x2="12.01" y1="17" y2="17"/>
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Formulir Pengaduan</h3>
              <p class="text-slate-500 text-sm">Sampaikan keluhan atau masalah Anda</p>
            </div>
          </div>

          @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
              {{ session('success') }}
            </div>
          @endif

          @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
              {{ session('error') }}
            </div>
          @endif

          <form id="pengaduanForm" class="space-y-4" method="POST" action="{{ route('pengaduan.store') }}">
            @csrf
            
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
              <input 
                type="text" 
                name="nama_lengkap" 
                placeholder="Masukkan nama lengkap Anda" 
                required 
                value="{{ old('nama_lengkap') }}"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition @error('nama_lengkap') border-red-500 ring-red-500/30 @enderror"
              />
              @error('nama_lengkap')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-navy-900 mb-2">Email <span class="text-red-500">*</span></label>
                <input 
                  type="email" 
                  name="email" 
                  placeholder="email@example.com" 
                  required 
                  value="{{ old('email') }}"
                  class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition @error('email') border-red-500 ring-red-500/30 @enderror"
                />
                @error('email')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-semibold text-navy-900 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                <input 
                  type="tel" 
                  name="telepon" 
                  placeholder="08xxxxxxxxx" 
                  required 
                  value="{{ old('telepon') }}"
                  class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition @error('telepon') border-red-500 ring-red-500/30 @enderror"
                />
                @error('telepon')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Kategori Pengaduan <span class="text-red-500">*</span></label>
              <select 
                name="kategori" 
                required 
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition bg-white @error('kategori') border-red-500 ring-red-500/30 @enderror"
              >
                <option value="">-- Pilih Kategori --</option>
                <option value="peminjaman_barang" {{ old('kategori') == 'peminjaman_barang' ? 'selected' : '' }}>Masalah Peminjaman Barang</option>
                <option value="pengembalian_barang" {{ old('kategori') == 'pengembalian_barang' ? 'selected' : '' }}>Masalah Pengembalian Barang</option>
                <option value="peminjaman_kendaraan" {{ old('kategori') == 'peminjaman_kendaraan' ? 'selected' : '' }}>Masalah Peminjaman Kendaraan</option>
                <option value="pengembalian_kendaraan" {{ old('kategori') == 'pengembalian_kendaraan' ? 'selected' : '' }}>Masalah Pengembalian Kendaraan</option>
                <option value="peminjaman_gedung" {{ old('kategori') == 'peminjaman_gedung' ? 'selected' : '' }}>Masalah Peminjaman Gedung</option>
                <option value="pengembalian_gedung" {{ old('kategori') == 'pengembalian_gedung' ? 'selected' : '' }}>Masalah Pengembalian Gedung</option>
                <option value="persediaan" {{ old('kategori') == 'persediaan' ? 'selected' : '' }}>Masalah Permintaan Persediaan</option>
                <option value="sistem" {{ old('kategori') == 'sistem' ? 'selected' : '' }}>Kendala Sistem/Aplikasi</option>
                <option value="layanan" {{ old('kategori') == 'layanan' ? 'selected' : '' }}>Kualitas Layanan</option>
                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
              </select>
              @error('kategori')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Deskripsi Pengaduan <span class="text-red-500">*</span></label>
              <textarea 
                name="deskripsi" 
                placeholder="Jelaskan secara detail masalah yang Anda alami..." 
                rows="5" 
                required 
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500 transition resize-none @error('deskripsi') border-red-500 ring-red-500/30 @enderror"
              >{{ old('deskripsi') }}</textarea>
              @error('deskripsi')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="setuju_kebijakan" required class="w-4 h-4 accent-red-500 rounded cursor-pointer" />
                <span class="text-sm text-slate-600">Saya setuju dengan <a href="#" class="text-red-600 hover:underline">kebijakan privasi</a> dan penggunaan data</span>
              </label>
              @error('setuju_kebijakan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <button 
              type="submit" 
              id="submitBtn"
              class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold py-2.5 rounded-lg hover:shadow-lg hover:from-red-600 hover:to-rose-700 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-pulse">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
                <polyline points="7 3 7 8 15 8"/>
              </svg>
              <span id="submitText">Kirim Pengaduan</span>
            </button>
          </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 p-8 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center border border-amber-100">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Survey Kepuasan Layanan</h3>
              <p class="text-slate-500 text-sm">Bantu kami meningkatkan kualitas layanan SIPANDU</p>
            </div>
          </div>

          @if(session('success_survey'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
              {{ session('success_survey') }}
            </div>
          @endif

          <form id="surveyForm" class="space-y-4" method="POST" action="{{ route('survey.store') }}">
            @csrf
            
            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Nama/Identitas <span class="text-red-500">*</span></label>
              <input 
                type="text" 
                name="nama" 
                placeholder="Masukkan nama atau NIP Anda" 
                required 
                value="{{ old('nama') }}"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition @error('nama') border-red-500 ring-red-500/30 @enderror"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Email <span class="text-red-500">*</span></label>
              <input 
                type="email" 
                name="email" 
                placeholder="email@example.com" 
                required 
                value="{{ old('email') }}"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition"
              />
            </div>
            
            <div class="space-y-3">
              <label class="block text-sm font-semibold text-navy-900 mb-3">Tingkat Kepuasan Anda terhadap Layanan SIPANDU <span class="text-red-500">*</span></label>
              <div class="space-y-2">
                <label class="flex items-center gap-3 cursor-pointer group p-3 bg-amber-50/50 rounded-xl hover:bg-amber-50 transition-all border border-amber-100 hover:border-amber-200">
                  <input type="radio" name="kepuasan" value="sangat_puas" required class="w-5 h-5 accent-amber-500 cursor-pointer" {{ old('kepuasan') == 'sangat_puas' ? 'checked' : '' }} />
                  <div class="flex items-center gap-2">
                    <span class="text-2xl text-amber-500">★★★★★</span>
                    <span class="text-sm font-semibold text-slate-800 group-hover:text-navy-900">Sangat Puas</span>
                  </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group p-3 bg-amber-50/50 rounded-xl hover:bg-amber-50 transition-all border border-amber-100 hover:border-amber-200">
                  <input type="radio" name="kepuasan" value="puas" required class="w-5 h-5 accent-amber-500 cursor-pointer" {{ old('kepuasan') == 'puas' ? 'checked' : '' }} />
                  <div class="flex items-center gap-2">
                    <span class="text-2xl text-amber-500">★★★★</span>
                    <span class="text-sm font-semibold text-slate-800 group-hover:text-navy-900">Puas</span>
                  </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group p-3 bg-amber-50/50 rounded-xl hover:bg-amber-50 transition-all border border-amber-100 hover:border-amber-200">
                  <input type="radio" name="kepuasan" value="cukup" required class="w-5 h-5 accent-amber-500 cursor-pointer" {{ old('kepuasan') == 'cukup' ? 'checked' : '' }} />
                  <div class="flex items-center gap-2">
                    <span class="text-2xl text-amber-500">★★★</span>
                    <span class="text-sm font-semibold text-slate-800 group-hover:text-navy-900">Cukup Puas</span>
                  </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group p-3 bg-amber-50/50 rounded-xl hover:bg-amber-50 transition-all border border-amber-100 hover:border-amber-200">
                  <input type="radio" name="kepuasan" value="kurang_puas" required class="w-5 h-5 accent-amber-500 cursor-pointer" {{ old('kepuasan') == 'kurang_puas' ? 'checked' : '' }} />
                  <div class="flex items-center gap-2">
                    <span class="text-2xl text-amber-500">★★</span>
                    <span class="text-sm font-semibold text-slate-800 group-hover:text-navy-900">Kurang Puas</span>
                  </div>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-navy-900 mb-2">Aspek yang Paling Memuaskan</label>
              <textarea 
                name="aspek_memuaskan" 
                placeholder="Sebutkan hal-hal yang Anda sukai dari layanan SIPANDU (fasilitas, kemudahan, pelayanan, dll)..." 
                rows="3" 
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 transition resize-none"
              >{{ old('aspek_memuaskan') }}</textarea>
            </div>

            <div>
              <label class="flex items-center gap-3 cursor-pointer p-3 bg-slate-50 rounded-xl border border-slate-200">
                <input type="checkbox" name="setuju_kebijakan" required class="w-5 h-5 accent-amber-500 rounded cursor-pointer" />
                <span class="text-sm text-slate-700">Saya setuju dengan <a href="#" class="text-amber-600 hover:underline font-semibold">kebijakan privasi</a></span>
              </label>
            </div>

            <button 
              type="submit" 
              class="w-full bg-gradient-to-r from-amber-500 to-yellow-600 text-white font-semibold py-3 rounded-xl hover:shadow-lg transition-all duration-200 flex items-center justify-center gap-2 text-base shadow-md"
            >
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-pulse">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
              </svg>
              <span>Kirim Survey & Bantu Kami Berkembang</span>
            </button>
          </form>
        </div>
      </div>
      <div class="mb-16 pt-10 border-t border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
          <div>
            <div class="inline-flex items-center gap-2 bg-blue-50 rounded-full px-4 py-1.5 mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M2 12h10"/><path d="M9 4v16"/><path d="m3 9 3 3-3 3"/></svg>
              <span class="text-navy-700 text-sm font-semibold">Live Tracking</span>
            </div>
            <h3 class="text-2xl font-bold text-navy-900">Pantauan Pengaduan Publik</h3>
            <p class="text-slate-500 text-sm mt-1">Transparansi tindak lanjut laporan dan keluhan dari masyarakat maupun pegawai</p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          @forelse($pengaduans as $item)
          <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col group">
            <div class="flex justify-between items-start mb-4">
              <span class="text-[11px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                {{ str_replace('_', ' ', $item->kategori) }}
              </span>
              
              @if($item->status == 'pending')
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-yellow-50 text-yellow-600 border border-yellow-200 flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Menunggu
                </span>
              @elseif($item->status == 'diproses')
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 border border-blue-200 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" class="animate-spin"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Diproses
                </span>
              @elseif($item->status == 'selesai')
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Selesai
                </span>
              @endif
            </div>
            
            <p class="text-navy-900 text-sm font-medium mb-4 line-clamp-3 group-hover:line-clamp-none transition-all">
              "{{ $item->deskripsi }}"
            </p>
            
            <div class="flex items-center justify-between text-xs text-slate-500 border-t border-slate-100 pt-4 mt-auto">
              <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-[10px]">
                  {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                </div>
                <span class="font-semibold text-slate-700">{{ substr($item->nama_lengkap, 0, 4) . '***' }}</span>
              </div>
              <span>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</span>
            </div>
          </div>

          @empty
          <div class="col-span-full bg-slate-50 rounded-2xl p-8 text-center border border-slate-200 border-dashed">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
            </div>
            <h4 class="text-navy-900 font-bold mb-1">Belum Ada Pengaduan</h4>
            <p class="text-slate-500 text-sm">Saat ini belum ada data pengaduan publik yang masuk ke dalam sistem.</p>
          </div>
          @endforelse
        </div>
      </div>
      <div class="grid md:grid-cols-3 gap-6 mb-10">
        <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-xl p-6 border border-red-100">
          <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h4 class="font-bold text-navy-900">Pengaduan Ditangani Dengan Serius</h4>
          </div>
          <p class="text-sm text-slate-600 leading-relaxed">Setiap pengaduan yang masuk akan kami verifikasi dan tindaklanjuti oleh tim terkait dalam waktu maksimal 5 hari kerja.</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-yellow-50 rounded-xl p-6 border border-amber-100">
          <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            </div>
            <h4 class="font-bold text-navy-900">Masukan Sangat Berharga</h4>
          </div>
          <p class="text-sm text-slate-600 leading-relaxed">Feedback dari survey membantu kami untuk terus melakukan evaluasi dan perbaikan berkelanjutan dalam pelayanan.</p>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
          <div class="flex items-start gap-3 mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <h4 class="font-bold text-navy-900">Komunikasi Terbuka</h4>
          </div>
          <p class="text-sm text-slate-600 leading-relaxed">Kami terbuka untuk mendengar setiap masukan, kritik, dan saran yang bertujuan meningkatkan kualitas layanan SIPANDU.</p>
        </div>
      </div>
    </div>
  </section> --}}

  <section id="kontak" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="inline-flex items-center gap-2 bg-blue-50 rounded-full px-4 py-1.5 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            <span class="text-navy-700 text-sm font-semibold">Lokasi Kami</span>
          </div>
          <h2 class="text-3xl font-bold text-navy-900 mb-6">Hubungi BPMP Gorontalo</h2>
          <div class="space-y-5">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-navy-900 text-sm">Alamat</h4>
                    <a href="https://maps.app.goo.gl/gRqbzLfozSsyygreA" target="_blank" class="text-slate-500 text-sm hover:text-blue-600 transition-colors duration-200">
                        Jl. Dr Zainal Umar Sadiki, Desa Tunggulo, Kec.Tilongkabila, Kab.Bone Bolanggo, Provinsi Gorontalo 96128
                    </a>
                </div>
            </div>

            <div class="flex items-start gap-4"><div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div><div><h4 class="font-semibold text-navy-900 text-sm">Telepon</h4><p class="text-slate-500 text-sm">081313266661</p></div></div>
            <div class="flex items-start gap-4"><div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></div><div><h4 class="font-semibold text-navy-900 text-sm">Email</h4><p class="text-slate-500 text-sm">bpmpgorontalo@kemdikdasmen.go.id</p></div></div>
            <div class="flex items-start gap-4"><div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><div><h4 class="font-semibold text-navy-900 text-sm">Jam Operasional</h4><p class="text-slate-500 text-sm">Senin – Kamis: 07.30 – 16.00 WITA</p><span class="text-slate-500 text-sm">Jumat 07.30-16.30 WITA</span></div></div>
          </div>
        </div>

        <a href="https://maps.app.goo.gl/gRqbzLfozSsyygreA" target="_blank" class="block group bg-slate-100 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300" style="height:350px;">
          <div class="w-full h-full bg-gradient-to-br from-navy-100 to-blue-100 flex items-center justify-center relative">
            <div class="text-center group-hover:scale-105 transition-transform duration-300">
              <div class="w-20 h-20 bg-white rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
              </div>
              <p class="text-navy-700 font-semibold">BPMP Provinsi Gorontalo</p>
              <p class="text-navy-500 text-sm mt-1">Kota Gorontalo, 96128</p>
              <span class="mt-4 inline-block bg-navy-600 text-white text-xs px-4 py-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">Buka di Google Maps →</span>
            </div>
            <div class="absolute top-4 left-4 bg-white/80 backdrop-blur rounded-lg px-3 py-1.5 text-xs text-navy-600 font-medium">📍 0.5488° N, 123.0568° E</div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <footer class="bg-navy-950 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-4 gap-8 mb-10">
        <div class="md:col-span-2">
          <div class="flex items-center gap-3 mb-4"><div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1.5" fill="#3355ff"/><rect x="14" y="3" width="7" height="7" rx="1.5" fill="#5a82ff"/><rect x="3" y="14" width="7" height="7" rx="1.5" fill="#5a82ff"/><rect x="14" y="14" width="7" height="7" rx="1.5" fill="#3355ff"/></svg></div><div><span class="font-bold text-lg">SIPANDU</span><span class="text-blue-300 text-xs block">BPMP Gorontalo</span></div></div>
          <p class="text-blue-200/70 text-sm leading-relaxed max-w-md">Sistem Informasi Pendataan dan Monitoring Barang Milik Negara pada Balai Penjaminan Mutu Pendidikan Provinsi Gorontalo. Mendukung tata kelola aset yang transparan dan akuntabel.</p>
        </div>
        <div>
          <h4 class="font-semibold mb-4 text-sm">Menu</h4>
          <div class="space-y-2">
            <a href="#hero"      class="block text-blue-200/70 text-sm hover:text-white transition">Beranda</a>
            <a href="#fitur"     class="block text-blue-200/70 text-sm hover:text-white transition">Fitur</a>
            <a href="#statistik" class="block text-blue-200/70 text-sm hover:text-white transition">Statistik</a>
            <a href="#fasilitas" class="block text-blue-200/70 text-sm hover:text-white transition">Fasilitas</a>
          </div>
        </div>
        <div>
          <h4 class="font-semibold mb-4 text-sm">Tautan</h4>
          <div class="space-y-2">
            <a href="#" class="block text-blue-200/70 text-sm hover:text-white transition">Kemendikdasmen</a>
            <a href="#" class="block text-blue-200/70 text-sm hover:text-white transition">SIMAK-BMN</a>
            <a href="#" class="block text-blue-200/70 text-sm hover:text-white transition">DJKN</a>
          </div>
        </div>
      </div>
      <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-blue-200/50 text-sm">© 2026 SIPANDU BPMP Provinsi Gorontalo. Hak Cipta Dilindungi.</p>
        <span class="text-blue-200/50 text-xs">Dibangun dengan ❤️ Dari Lintang Cahyani Putri</span>
      </div>
    </div>
  </footer>

  <div id="facilityModal" class="modal-overlay hidden" onclick="handleModalOverlayClick(event)">
    <div class="modal-box" onclick="event.stopPropagation()">
      <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 flex-shrink-0">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-navy-600"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg></div>
          <span class="font-bold text-navy-900 text-base">Detail Fasilitas</span>
        </div>
        <button onclick="closeModal()" class="w-9 h-9 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-500 hover:text-navy-700 transition"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
      </div>
      <div class="modal-body flex-1">
        <div class="flex flex-col lg:flex-row">
          <div class="lg:w-[52%] flex-shrink-0">
            <div class="relative overflow-hidden bg-slate-100" style="height:280px;">
              <img id="modalMainImg" src="" alt="" class="modal-img w-full h-full object-cover">
              <div class="absolute top-4 left-4"><span id="modalAvailBadge" class="text-xs font-bold px-3 py-1.5 rounded-full"></span></div>
              <div class="absolute bottom-4 left-4 flex items-center gap-1.5 bg-navy-900/80 backdrop-blur text-white rounded-xl px-3 py-1.5">
                <span id="modalRatingScore" class="font-bold text-sm"></span>
                <div id="modalStars" class="flex gap-0.5"></div>
                <span id="modalReviewCount" class="text-white/70 text-xs"></span>
              </div>
            </div>
            <div id="modalThumbs" class="flex gap-2 p-4 overflow-x-auto"></div>
          </div>
          <div class="flex-1 p-6 lg:p-7 flex flex-col overflow-y-auto">
            <div class="mb-4">
              <h2 id="modalName" class="text-xl font-extrabold text-navy-900 leading-tight mb-1"></h2>
              <div class="flex items-center gap-1.5 text-slate-500 text-sm"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg><span id="modalLocation"></span></div>
            </div>
            <div class="grid grid-cols-2 gap-2 mb-5" id="modalSpecs"></div>
            <div class="mb-5"><h4 class="text-xs font-bold text-navy-700 uppercase tracking-wide mb-2">Deskripsi</h4><p id="modalDesc" class="text-slate-600 text-sm leading-relaxed"></p></div>
            <div class="mb-5"><h4 class="text-xs font-bold text-navy-700 uppercase tracking-wide mb-2">Fasilitas yang Tersedia</h4><div id="modalFeatures" class="flex flex-wrap gap-1.5"></div></div>
            <div class="mb-5"><h4 class="text-xs font-bold text-navy-700 uppercase tracking-wide mb-2">Ketentuan Penggunaan</h4><ul id="modalRules" class="space-y-1.5"></ul></div>
            <div class="mt-auto pt-4 border-t border-slate-100">
              <div class="flex items-end justify-between flex-wrap gap-3">
                <div>
                  <div id="modalPriceOld" class="text-slate-400 text-xs line-through mb-0.5"></div>
                  <div id="modalPrice" class="text-xl font-extrabold text-navy-900"></div>
                  <div class="text-slate-400 text-xs mt-0.5"></div>
                </div>
                <div class="flex gap-2">
                  <button onclick="closeModal()" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-semibold hover:bg-slate-50 transition">Kembali</button>
                  <a href="{{ route('login') }}" class="btn-primary px-5 py-2.5 rounded-xl text-white text-sm font-semibold flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="13" x="2" y="5" rx="2"/><path d="m22 8-8 5-8-5"/></svg>
                    Hubungi Kami
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="toastContainer" class="fixed top-4 right-4 z-[300] space-y-2 pointer-events-none"></div>

<script>

const FACILITIES = [
  { 
    name: 'Kantor Ponuwa 1', 
    category: 'kantor', 
    location: 'Gedung Kantor Utama', 
    capacity: '100 Staf', luas: '500 m²', 
    operasional: 'Senin–Jumat 07.30–16.00 WITA', kontak: '(0435) 821-555', 
    description: 'Pusat administrasi dan layanan penjaminan mutu pendidikan Provinsi Gorontalo yang melayani koordinasi antar instansi pendidikan.',
    features: ['Ruang Tunggu Nyaman', 'Unit Pelayanan Terpadu (ULT)', 'Ruang Tamu VIP', 'Koneksi Internet ', 'CCTV & Keamanan 24 Jam'], 
    rules: ['Tamu wajib melapor ke petugas keamanan', 'Berpakaian rapi dan sopan', 'Dilarang merokok di area kerja', 'Menjaga ketertiban selama jam kantor'], 
    images: [
      "{{ asset('storage/fasilitas/kantor_utama.jpeg') }}",
      "{{ asset('storage/fasilitas/ult.jpeg') }}",
      "{{ asset('storage/fasilitas/ruangtungguult.jpeg') }}",
      "{{ asset('storage/fasilitas/humas.jpeg') }}",
      "{{ asset('storage/fasilitas/ruangsubagumum.jpeg') }}",
      "{{ asset('storage/fasilitas/ruangkeuanganfix.jpg') }}",
      "{{ asset('storage/fasilitas/ruangsubagumum.jpeg') }}"

      
    ]
  },
  { 
    name: 'Kantor Ponuwa 2', 
    category: 'kantor', 
    location: 'Gedung Kantor Utama', 
    capacity: '100 Staf', luas: '500 m²', 
    operasional: 'Senin–Jumat 07.30–16.00 WITA', kontak: '(0435) 821-555', 
    description: 'Pusat administrasi dan layanan penjaminan mutu pendidikan Provinsi Gorontalo yang melayani koordinasi antar instansi pendidikan.',
    features: ['Ruang Tunggu Nyaman', 'Unit Pelayanan Terpadu (ULT)', 'Ruang Tamu VIP', 'Koneksi Internet ', 'CCTV & Keamanan 24 Jam'], 
    rules: ['Tamu wajib melapor ke petugas keamanan', 'Berpakaian rapi dan sopan', 'Dilarang merokok di area kerja', 'Menjaga ketertiban selama jam kantor'], 
    images: [
      "{{ asset('storage/fasilitas/Kantorponuwa_2.jpeg') }}",
      "{{ asset('storage/fasilitas/timkerpaud.jpeg') }}",
      "{{ asset('storage/fasilitas/timkersd.jpeg') }}",
      "{{ asset('storage/fasilitas/timkersmp.jpeg') }}",
      "{{ asset('storage/fasilitas/timkersma2.jpg') }}",
    ]
  },
  { 
    name: 'Ruang Pertemuan Aula Dulohupa', 
    category: 'ruang', 
    location: 'Gedung Aula Utama', 
    capacity: '200 - 300 Orang', luas: '420 m²', 
    operasional: 'Senin–Minggu (Sesuai Reservasi)', kontak: '(0435) 821-555 ext. 101', 
    description: 'Aula serbaguna yang luas, ideal untuk seminar, lokakarya, dan pertemuan skala besar dengan fasilitas audio visual lengkap.',
    features: ['AC', 'Standar Sound System ', 'vidio trond', 'Meja Kursi', 'Toilet Bersih'], 
    rules: ['Pemesanan minimal H-7', 'Dilarang membawa makanan berbau tajam ke dalam ruangan', 'Penyewa bertanggung jawab atas kebersihan', 'Jam operasional sesuai izin penggunaan'], 
    images: [
      "{{ asset('storage/fasilitas/gedung_aula.jpeg') }}",
      "{{ asset('storage/fasilitas/aula2.jpg') }}", 
    ]
  },
  { 
    name: 'Ruang Pertemuan Huyula', 
    category: 'ruang', 
    location: 'Gedung Pertemuan Huyula', 
    capacity: '200 - 300 Orang', luas: '420 m²', 
    operasional: 'Senin–Minggu (Sesuai Reservasi)', kontak: '(0435) 821-555 ext. 101', 
    description: 'Aula serbaguna yang luas, ideal untuk seminar, lokakarya, dan pertemuan skala besar dengan fasilitas audio visual lengkap.',
    features: ['AC', 'Standar Sound System ', 'vidio trond', 'Meja Kursi', 'Toilet Bersih'], 
    rules: ['Pemesanan minimal H-7', 'Dilarang membawa makanan berbau tajam ke dalam ruangan', 'Penyewa bertanggung jawab atas kebersihan', 'Jam operasional sesuai izin penggunaan'], 
    images: [
      "{{ asset('storage/fasilitas/gedung_huyula.jpg') }}",
      "{{ asset('storage/fasilitas/ruang_sidang.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tilango 1 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '140 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/tilango.jpg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tilango 2 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/TILANGO_2.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tilango 3 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/TILANGO_3.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tinelo 1 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/tinelo_1.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tinelo 2 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/TINELO_2.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tinelo 3 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/tinelo_3.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tinelo 4 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/tinelo_4.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Kelas Tinelo 5 BPMP Gorontalo', 
    category: 'kelas', 
    location: 'BPMP Gorontalo', 
    capacity: '30 Orang/Kelas', luas: '68 m²', 
    operasional: 'Senin–Sabtu 07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Ruang belajar yang representatif untuk kegiatan pelatihan, bimtek, atau kursus dengan suasana yang tenang dan kondusif.',
    features: ['AC ', 'Proyektor', 'Papan Tulis Whiteboard', 'meja kursi', 'Sound system'], 
    rules: ['Dilarang mencoret-coret meja/kursi', 'Matikan alat elektronik setelah selesai digunakan', 'Tidak membawa barang yang terdapat dalm ruangan ke area luar BPMP Gorontalo', 'Tidak merusak barang yang ada dalam ruangan'], 
    images: [
      "{{ asset('storage/fasilitas/tinelo_5.jpeg') }}"
    ]
  },
  
  { 
    name: 'Mess Bandayo kiki 1 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Area Mess Bandayo', 
    capacity: '4 Orang 2 Kamar', luas: '136.50 m²', 
    operasional: '24 Jam (Check-in 14.00, Check-out 12.00)', kontak: '0813-1326-6661', 
    description: 'Fasilitas penginapan bagi tamu dinas atau peserta diklat dengan suasana asri yang menjamin istirahat berkualitas.',
    features: [ 'Kamar Mandi Dalam', 'kamar tidur', 'Ruang kumpul Bersama', 'Ac'], 
    rules: ['Dilarang membawa senjata tajam/narkoba', 'Dilarang merokok di dalam kamar', 'Menyerahkan kartu identitas saat check-in', 'Menjaga ketenangan di jam istirahat'], 
    images: [
      "{{ asset('storage/fasilitas/bandayokiki_1.jpeg') }}", 
      "{{ asset('storage/fasilitas/dalambandayokiki1.jpeg') }}",
      "{{ asset('storage/fasilitas/dalam_bandayokiki.jpeg') }}",
    ]
  },
  { 
    name: 'Mess Bandayo kiki 2 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Area Mess Bandayo', 
    capacity: '4 Orang 2 Kamar', luas: '120,75 m²', 
    operasional: '24 Jam (Check-in 14.00, Check-out 12.00)', kontak: '0813-1326-6661', 
    description: 'Fasilitas penginapan bagi tamu dinas atau peserta diklat dengan suasana asri yang menjamin istirahat berkualitas.',
    features: [ 'Kamar Mandi Dalam', 'kamar tidur', 'Ruang kumpul Bersama', 'Ac'], 
    rules: ['Dilarang membawa senjata tajam/narkoba', 'Dilarang merokok di dalam kamar', 'Menyerahkan kartu identitas saat check-in', 'Menjaga ketenangan di jam istirahat'], 
    images: [
      "{{ asset('storage/fasilitas/bandayokiki.jpeg') }}", 
      "{{ asset('storage/fasilitas/dalambandayokiki2.jpeg') }}",
      "{{ asset('storage/fasilitas/kamarbandayokiki2.jpeg') }}"
    ]
  },
  { 
    name: 'Mess Bandayo kiki 3 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Area Mess Bandayo', 
    capacity: '4 Orang 2 Kamar', luas: '120,75 m²', 
    operasional: '24 Jam (Check-in 14.00, Check-out 12.00)', kontak: '0813-1326-6661', 
    description: 'Fasilitas penginapan bagi tamu dinas atau peserta diklat dengan suasana asri yang menjamin istirahat berkualitas.',
    features: [ 'Kamar Mandi Dalam', 'kamar tidur', 'Ruang kumpul Bersama', 'Ac'], 
    rules: ['Dilarang membawa senjata tajam/narkoba', 'Dilarang merokok di dalam kamar', 'Menyerahkan kartu identitas saat check-in', 'Menjaga ketenangan di jam istirahat'], 
    images: [
      "{{ asset('storage/fasilitas/bandayokiki3.jpeg') }}",
      "{{ asset('storage/fasilitas/dalambandayokiki3.jpeg') }}",
      "{{ asset('storage/fasilitas/kamarbandayokiki3.jpeg') }}",
    ]
  },
  { 
    name: 'Mess Bandayo kiki 4 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Area Mess Bandayo', 
    capacity: '4 Orang 2 Kamar', luas: '120,75 m²', 
    operasional: '24 Jam (Check-in 14.00, Check-out 12.00)', kontak: '0813-1326-6661', 
    description: 'Fasilitas penginapan bagi tamu dinas atau peserta diklat dengan suasana asri yang menjamin istirahat berkualitas.',
    features: [ 'Kamar Mandi Dalam', 'kamar tidur', 'Ruang kumpul Bersama', 'Ac'], 
    rules: ['Dilarang membawa senjata tajam/narkoba', 'Dilarang merokok di dalam kamar', 'Menyerahkan kartu identitas saat check-in', 'Menjaga ketenangan di jam istirahat'], 
    images: [
      "{{ asset('storage/fasilitas/bandayokiki4.jpeg') }}",
      "{{ asset('storage/fasilitas/dalambandayokiki4.jpeg') }}",
      "{{ asset('storage/fasilitas/kamarbandayokiki4.jpeg') }}",
    ]
  },
  { 
    name: 'Mess Bandayo Daa BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Area Mess Bandayo', 
    capacity: '12 Orang 6 Kamar', luas: '211,50 m²', 
    operasional: '24 Jam (Check-in 14.00, Check-out 12.00)', kontak: '0813-1326-6661', 
    description: 'Fasilitas penginapan bagi tamu dinas atau peserta diklat dengan suasana asri yang menjamin istirahat berkualitas.',
    features: [ 'Kamar Mandi Dalam', 'kamar tidur', 'Ruang kumpul Bersama', 'Ac'], 
    rules: ['Dilarang membawa senjata tajam/narkoba', 'Dilarang merokok di dalam kamar', 'Menyerahkan kartu identitas saat check-in', 'Menjaga ketenangan di jam istirahat'], 
    images: [
      "{{ asset('storage/fasilitas/bandayodaa.jpeg') }}",
      "{{ asset('storage/fasilitas/bandayo_daa.jpeg') }}",
      "{{ asset('storage/fasilitas/dalambandayodaa.jpeg') }}",
    ]
  },
  
  { 
    name: 'Ruang Asrama Beledaa 1 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Gedung Asrama Bele Daa, Wongkaditi Timur', 
    capacity: '32 Orang 16 Kamar', luas: '522,00 m²', 
    operasional: '24 Jam', kontak: '0813-1326-6661', 
    description: 'Akomodasi tipe asrama yang luas untuk menampung peserta kegiatan dalam jumlah banyak dengan fasilitas pendukung yang lengkap.',
    features: ['Tempat Tidur',  'Kamar Mandi Dalam', 'Ruang Berkumpul Bersama'], 
    rules: ['Wajib menjaga kebersihan area bersama', 'Tidak merusak barang yang ada dalam ruangan', 'Mengembalikan kunci kepada petugas pada saat Chek Out', 'Tidak membawa pasangan bukan muhrim ke dalam kamar','Mengunci pintu saat bepergian'], 
    images: [
      "{{ asset('storage/fasilitas/beledaa1.jpeg') }}",
    ]
  },
  { 
    name: 'Ruang Asrama Beledaa 2 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Gedung Asrama Bele Daa, Wongkaditi Timur', 
    capacity: '32 Orang 16 Kamar', luas: '522,00 m²', 
    operasional: '24 Jam', kontak: '0813-1326-6661', 
    description: 'Akomodasi tipe asrama yang luas untuk menampung peserta kegiatan dalam jumlah banyak dengan fasilitas pendukung yang lengkap.',
    features: ['Tempat Tidur',  'Kamar Mandi Dalam', 'Ruang Berkumpul Bersama'], 
    rules: ['Wajib menjaga kebersihan area bersama', 'Tidak merusak barang yang ada dalam ruangan', 'Mengembalikan kunci kepada petugas pada saat Chek Out', 'Tidak membawa pasangan bukan muhrim ke dalam kamar','Mengunci pintu saat bepergian'], 
    images: [
       "{{ asset('storage/fasilitas/asrama_baledaa.jpeg') }}",
    ]
  },
  { 
    name: 'Ruang Asrama Beledaa 3 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Gedung Asrama Bele Daa, Wongkaditi Timur', 
    capacity: '32 Orang 16 Kamar', luas: '522,00 m²', 
    operasional: '24 Jam', kontak: '0813-1326-6661', 
    description: 'Akomodasi tipe asrama yang luas untuk menampung peserta kegiatan dalam jumlah banyak dengan fasilitas pendukung yang lengkap.',
    features: ['Tempat Tidur',  'Kamar Mandi Dalam', 'Ruang Berkumpul Bersama'], 
    rules: ['Wajib menjaga kebersihan area bersama', 'Tidak merusak barang yang ada dalam ruangan', 'Mengembalikan kunci kepada petugas pada saat Chek Out', 'Tidak membawa pasangan bukan muhrim ke dalam kamar','Mengunci pintu saat bepergian'], 
    images: [
      "{{ asset('storage/fasilitas/beledaa3.jpg') }}",
    ]
  },
  { 
    name: 'Ruang Asrama Beledaa 4 BPMP Gorontalo', 
    category: 'penginapan', 
    location: 'Gedung Asrama Bele Daa, Wongkaditi Timur', 
    capacity: '32 Orang 16 Kamar', luas: '522,00 m²', 
    operasional: '24 Jam', kontak: '0813-1326-6661', 
    description: 'Akomodasi tipe asrama yang luas untuk menampung peserta kegiatan dalam jumlah banyak dengan fasilitas pendukung yang lengkap.',
    features: ['Tempat Tidur',  'Kamar Mandi Dalam', 'Ruang Berkumpul Bersama'], 
    rules: ['Wajib menjaga kebersihan area bersama', 'Tidak merusak barang yang ada dalam ruangan', 'Mengembalikan kunci kepada petugas pada saat Chek Out', 'Tidak membawa pasangan bukan muhrim ke dalam kamar','Mengunci pintu saat bepergian'], 
    images: [
      "{{ asset('storage/fasilitas/beledaa4.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Makan Olamita 1 BPMP Gorontalo', 
    category: 'ruang_makan',  
    location: 'Gedung Olamita, Wongkaditi Timur', 
    capacity: '160 Orang', luas: '198 m²', 
    operasional: '07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Area makan bersih dan higienis yang melayani konsumsi peserta diklat maupun tamu umum dengan sistem prasmanan.',
    features: ['Meja & Kursi Makan ', 'Area Cuci Tangan (Wastafel)', 'Gazebo Outdoor Olamita', 'Toilet', 'Ac'], 
    rules: ['Budayakan antre', 'Dilarang menyisakan makanan (Zero Waste)', 'Kembalikan peralatan makan ke tempat yang disediakan', 'Dilarang merokok'], 
    images: [
      "{{ asset('storage/fasilitas/olamita1_depan.jpeg') }}",
      "{{ asset('storage/fasilitas/olamita_1.jpeg') }}",
      "{{ asset('storage/fasilitas/dalamolamita_1.jpeg') }}"
    ]
  },
  
  { 
    name: 'Ruang Makan Olamita 2 BPMP Gorontalo', 
    category: 'ruang_makan',  
    location: 'Gedung Olamita, Wongkaditi Timur', 
    capacity: '40 Orang', luas: '198 m²', 
    operasional: '07.30–16.00 WITA', kontak: ' 0813-1326-6661', 
    description: 'Area makan bersih dan higienis yang melayani konsumsi peserta diklat maupun tamu umum dengan sistem prasmanan.',
    features: ['Meja & Kursi Makan ', 'Area Cuci Tangan (Wastafel)', 'Gazebo Outdoor Olamita', 'Toilet', 'Ac'], 
    rules: ['Budayakan antre', 'Dilarang menyisakan makanan (Zero Waste)', 'Kembalikan peralatan makan ke tempat yang disediakan', 'Dilarang merokok'], 
    images: [
      "{{ asset('storage/fasilitas/olamita_2.jpeg') }}",
      "{{ asset('storage/fasilitas/dalamolamita_2.jpeg') }}"
    ]
  },
  { 
    name: 'Lapangan Tenis BPMP Gorontalo', 
    category: 'outdoor', 
    location: 'Area Sport Center BPMP', 
    capacity: 'Area Terbuka', luas: '800 m²', 
    operasional: '06.00–18.00 WITA', kontak: 'Keamanan', 
    description: 'Fasilitas olahraga luar ruangan untuk menjaga kebugaran, terdiri dari lapangan tenis dan area jogging yang sejuk.',
    features: ['Lapangan Tenis Hardcourt'], 
    rules: ['Gunakan pakaian dan sepatu olahraga yang sesuai', 'Dilarang merusak fasilitas lapangan', 'Menjaga kebersihan area lapangan', 'Penggunaan malam hari harus seizin pengelola'], 
    images: [
      "{{ asset('storage/fasilitas/lapangan_tenis.jpeg') }}",
    ]
  },
  { 
    name: 'Lapangan Voli/Takraw BPMP Gorontalo', 
    category: 'outdoor', 
    location: 'Area Sport Center BPMP', 
    capacity: 'Area Terbuka', luas: '800 m²', 
    operasional: '06.00–18.00 WITA', kontak: 'Keamanan', 
    description: 'Fasilitas olahraga luar ruangan untuk menjaga kebugaran, terdiri dari lapangan tenis dan area jogging yang sejuk.',
    features: ['Lapangan Voli/Takraw'], 
    rules: ['Gunakan pakaian dan sepatu olahraga yang sesuai', 'Dilarang merusak fasilitas lapangan', 'Menjaga kebersihan area lapangan', 'Penggunaan malam hari harus seizin pengelola'], 
    images: [
      "{{ asset('storage/fasilitas/lapangan_olahraga.jpeg') }}", 
    ]
  },{ 
    name: 'Jogging Track BPMP Gorontalo', 
    category: 'outdoor', 
    location: 'Area Sport Center BPMP', 
    capacity: 'Area Terbuka', luas: '800 m²', 
    operasional: '06.00–18.00 WITA', kontak: 'Keamanan', 
    description: 'Fasilitas olahraga luar ruangan untuk menjaga kebugaran, terdiri dari lapangan tenis dan area jogging yang sejuk.',
    features: [ 'Jogging Track'], 
    rules: ['Gunakan pakaian dan sepatu olahraga yang sesuai', 'Dilarang merusak fasilitas lapangan', 'Menjaga kebersihan area lapangan', 'Penggunaan malam hari harus seizin pengelola'], 
    images: [
      "{{ asset('storage/fasilitas/jogging_treck.jpeg') }}"
    ]
  },{ 
    name: 'Lapangan Fustal BPMP Gorontalo', 
    category: 'outdoor', 
    location: 'Area Sport Center BPMP', 
    capacity: 'Area Terbuka', luas: '800 m²', 
    operasional: '06.00–18.00 WITA', kontak: 'Keamanan', 
    description: 'Fasilitas olahraga luar ruangan untuk menjaga kebugaran, terdiri dari lapangan tenis dan area jogging yang sejuk.',
    features: [ 'Lapangan Futsal'], 
    rules: ['Gunakan pakaian dan sepatu olahraga yang sesuai', 'Dilarang merusak fasilitas lapangan', 'Menjaga kebersihan area lapangan', 'Penggunaan malam hari harus seizin pengelola'], 
    images: [
      "{{ asset('storage/fasilitas/lapanganfutsal.jpeg') }}"
    ]
  },
  { 
    name: 'Mushollah BPMP Gorontalo', 
    category: 'sarana_ibadah',
    location: 'Samping Gedung Utama', 
    capacity: '50 Orang', luas: '100 m²', 
    operasional: '24 Jam (Waktu Shalat)', kontak: '-', 
    description: 'Sarana ibadah yang bersih dan tenang bagi pegawai maupun tamu untuk melaksanakan shalat lima waktu.',
    features: ['Mukena Bersih', 'Tempat Wudhu Terpisah (Pria/Wanita)', 'Sound System Adzan', 'Toilet'], 
    rules: ['Menjaga ketenangan dan kesucian tempat', 'Meletakkan alas kaki di rak yang tersedia', 'Matikan lampu dan AC setelah digunakan (jika tidak ada orang)', 'Dilarang tidur di dalam mushollah'], 
    images: [
      "{{ asset('storage/fasilitas/musolla_depan.jpg') }}"
    ]
  },
  { 
    name: 'Klinik BPMP Gorontalo', 
    category: 'kesehatan', 
    location: 'Gedung Layanan Kesehatan, Lantai 1', 
    capacity: '5 Pasien', luas: '40 m²', 
    operasional: 'Senin–Jumat 08.00–15.00 WITA', kontak: 'Unit Kesehatan', 
    description: 'Fasilitas kesehatan dasar untuk penanganan pertama bagi pegawai atau peserta diklat yang mengalami gangguan kesehatan ringan.',
    features: [ 'Kotak P3K Lengkap', 'Obat-obatan Standar', 'kursi roda', 'tempat tidur pasien' ], 
    rules: ['Hanya untuk penanganan medis darurat/ringan', 'Wajib mengisi buku kunjungan pasien', 'Pasien dengan kondisi berat akan dirujuk ke RS terdekat'], 
    images: [
      "{{ asset('storage/fasilitas/klinik.jpeg') }}"
    ]
  },
  { 
    name: 'Lapangan Upacara BPMP Gorontalo', 
    category: 'lapangan_Upacara', 
    location: 'Halaman Depan Gedung Utama', 
    capacity: '500 Orang', luas: '1200 m²', 
    operasional: 'Senin–Jumat 07.00–17.00 WITA', kontak: 'Satpam', 
    description: 'Lapangan terbuka yang luas untuk pelaksanaan upacara bendera, apel pagi, serta kegiatan senam bersama setiap hari Jumat.',
    features: ['Tiang Bendera', 'Lantai Paving Blok Rata', 'Sound System Luar Ruang', 'podium upacara'], 
    rules: ['Dilarang memarkir kendaraan di tengah lapangan saat jam upacara', 'Menjaga kebersihan area lapangan', 'Izin khusus untuk kegiatan tenda/panggung besar'], 
    images: [
      "{{ asset('storage/fasilitas/upacara.jpg') }}"
    ]
  },
  { 
    name: 'Gedung Arsip BPMP Gorontalo', 
    category: 'gedung', 
    location: 'Area Belakang, ', 
    capacity: 'Penyimpanan Dokumen', luas: '150 m²', 
    operasional: 'Senin–Jumat 08.00–16.00 WITA', kontak: 'Unit Kearsipan', 
    description: 'Fasilitas penyimpanan dokumen negara dan data penting lembaga yang dikelola secara sistematis dan aman.',
    features: ['Lemari arsip'], 
    rules: ['Hanya petugas berwenang yang boleh masuk', 'Dilarang membawa cairan atau benda mudah terbakar', 'Wajib mencatat pengambilan dokumen di buku log'], 
    images: [
      "{{ asset('storage/fasilitas/gedung_arsip.jpeg') }}"
    ]
  },
  { 
    name: 'Ruang Laboratorium BPMP Gorontalo', 
    category: 'Ruang', 
    location: 'Gedung Laboratorium Lt. 2', 
    capacity: '30 Orang', luas: '61 m²', 
    operasional: 'Sesuai Jadwal Pelatihan', kontak: 'Unit TIK', 
    description: 'Laboratorium komputer dan multimedia untuk uji kompetensi, pelatihan IT, serta pengembangan media pembelajaran digital.',
    features: [''], 
    rules: ['Dilarang mengubah konfigurasi software/hardware', 'Dilarang membawa makanan/minuman ke meja komputer', 'Gunakan alas kaki khusus atau melepas alas kaki'], 
    images: [
      "{{ asset('storage/fasilitas/gedung_laboratorium.jpeg') }}"
    ]
  }
];

const FACILITY_CHIPS = [
  { id:'semua', label:'Semua' },
  { id:'kantor',   label:'Kantor' },
  { id:'ruang', label:'Ruang Pertemuan' },
  { id:'kelas',   label:'Ruang Kelas' },
  { id:'penginapan',   label:'Asrama/mess' },
  { id:'ruang_makan', label:'Ruang Makan' },
  { id:'gedung',   label:'Gedung Arsip' },
  { id:'outdoor',   label:'Fasilitas Olahraga' },
  
  { id:'lapangan_Upacara',   label:'Lapangan Upacara' },
  { id:'sarana_ibadah',   label:'Sarana Ibadah' },
  { id:'kesehatan',   label:'Kesehatan' },
];

const DEMO_ACCOUNTS = [
  { username:'superadmin',     password:'super123',      label:'Super Admin',         color:'from-red-500 to-rose-600',     icon:'shield',    desc:'Akses penuh ke seluruh sistem' },
  { username:'kepalabpmp',     password:'kepala123',     label:'Kepala BPMP',            color:'from-purple-500 to-indigo-600',icon:'crown',     desc:'Dashboard eksekutif & persetujuan' },
  { username:'kasubag',        password:'kasubag123',    label:'Kasubag TU',             color:'from-indigo-500 to-blue-600',  icon:'briefcase', desc:'Verifikasi & koordinasi BMN' },
  { username:'adminpersediaan',password:'persediaan123', label:'Admin Persediaan',      color:'from-emerald-500 to-green-600',icon:'package',   desc:'Kelola stok & barang habis pakai' },
  { username:'adminsarpras',   password:'sarpras123',    label:'Admin Sarana Prasarana',color:'from-cyan-500 to-teal-600',   icon:'building',  desc:'Monitor gedung & fasilitas' },
  { username:'adminaset',      password:'aset123',       label:'Admin Aset Tetap',      color:'from-amber-500 to-yellow-600', icon:'landmark',  desc:'Inventarisasi aset tetap' },
  { username:'pegawai',        password:'pegawai123',    label:'Pegawai',               color:'from-slate-500 to-gray-600',   icon:'user',      desc:'Peminjaman & riwayat BMN' },
  { username:'tamu',           password:'tamu123',       label:'Tamu',                  color:'from-cyan-400 to-blue-500',    icon:'eye',       desc:'Melihat info & statistik BMN' },
];

let facilityFilter = 'semua';
let activeModalFacility = null;
const PROMO_END = new Date('2026-04-30T23:59:59');

// Countdown
function updateCountdown() {
  const hEl = document.getElementById('cdH'), mEl = document.getElementById('cdM'), sEl = document.getElementById('cdS');
  if (!hEl) return;
  let ms = PROMO_END - Date.now(); if (ms < 0) ms = 0;
  const s = Math.floor(ms / 1000);
  hEl.textContent = String(Math.floor(s / 3600)).padStart(2,'0');
  mEl.textContent = String(Math.floor((s % 3600) / 60)).padStart(2,'0');
  sEl.textContent = String(s % 60).padStart(2,'0');
}
updateCountdown(); setInterval(updateCountdown, 1000);

function starRow(rating, size='13px') {
  const n = Math.round(rating);
  return [0,1,2,3,4].map(i => `<span style="font-size:${size};line-height:1;color:${i < n ? '#f59e0b' : '#e2e8f0'}">★</span>`).join('');
}

function buildCarousel() {
  const chipsEl  = document.getElementById('facilityChips');
  const scrollEl = document.getElementById('facilitiesCarouselScroll');
  if (!chipsEl || !scrollEl) return;
  chipsEl.innerHTML = FACILITY_CHIPS.map(c => {
    const active = facilityFilter === c.id;
    return `<button type="button" onclick="setFacilityFilter('${c.id}')" class="px-4 py-2 rounded-full text-sm font-semibold transition border-2 ${active ? 'border-blue-500 bg-blue-50 text-navy-800 shadow-sm' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'}">${c.label}</button>`;
  }).join('');
  const list = facilityFilter === 'semua' ? FACILITIES : FACILITIES.filter(f => f.category === facilityFilter);
  if (!list.length) { scrollEl.innerHTML = '<p class="text-slate-500 text-sm py-8 px-4">Tidak ada fasilitas pada kategori ini.</p>'; return; }
  scrollEl.innerHTML = list.map(f => {
    const globalIdx = FACILITIES.indexOf(f);
    // const rs = String(f.rating).replace('.', ',');
    // const op = f.priceOld ? `<div class="text-slate-400 text-xs line-through mb-0.5">${f.priceOld}<span class="text-slate-300">/hari</span></div>` : '';
    return `<article class="facility-card flex-shrink-0 w-[260px] sm:w-[280px] snap-start rounded-2xl bg-white shadow-md border border-slate-100/80 overflow-hidden card-hover flex flex-col" onclick="openModal(${globalIdx})" title="Klik untuk lihat detail">
      <div class="relative overflow-hidden" style="aspect-ratio:4/3;">
        <img src="${f.images[0]}" alt="${f.name}" class="card-img w-full h-full object-cover" loading="lazy">
        <div class="card-overlay absolute inset-0 flex flex-col justify-end p-3"><div class="view-detail-btn inline-flex items-center gap-1.5 bg-white/90 backdrop-blur text-navy-800 text-xs font-bold px-3 py-1.5 rounded-full self-start shadow"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>Lihat Detail</div></div>
        ${f.images.length > 1 ? `<div class="absolute top-2 right-2 flex gap-0.5">${f.images.map((_,i)=>`<div class="w-1.5 h-1.5 rounded-full ${i===0?'bg-white':'bg-white/50'}"></div>`).join('')}</div>` : ''}
      </div>
      <div class="p-3.5 flex flex-col flex-1 space-y-1.5">
      <h3 class="font-bold text-navy-900 text-sm leading-snug line-clamp-2">${f.name}</h3>
      
      <p class="text-slate-500 text-xs flex items-center gap-1">
        📍 <span class="truncate">${f.location}</span>
      </p>

      <div class="space-y-1 mb-3">
        <p class="text-xs font-medium text-slate-700 mb-1">Fasilitas:</p>
        <div class="flex flex-wrap gap-1">
          ${f.features.slice(0,4).map(feat => `<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">${feat}</span>`).join('')}
        
        </div>
      </div>
      
      <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
        <span>📏 ${f.luas}</span>
        <span>👥 ${f.capacity}</span>
        <span>🕒 ${f.operasional.split(' ')[0]}</span>
      </div>
      <p class="text-emerald-600 text-xs font-medium flex items-center gap-1 pt-1 border-t border-slate-200/50 mt-auto">
        📞 ${f.kontak}
      </p>
    </div>
    </article>`;
  }).join('');
}

function setFacilityFilter(id) { facilityFilter = id; buildCarousel(); }
function facilitiesScrollNext() { const el = document.getElementById('facilitiesCarouselScroll'); if (el) el.scrollBy({ left: 300, behavior:'smooth' }); }
function facilitiesScrollPrev() { const el = document.getElementById('facilitiesCarouselScroll'); if (el) el.scrollBy({ left: -300, behavior:'smooth' }); }

function openModal(idx) {
  const f = FACILITIES[idx]; if (!f) return;
  activeModalFacility = f;
  const mainImg = document.getElementById('modalMainImg');
  mainImg.src = f.images[0]; mainImg.alt = f.name;
  mainImg.className = 'modal-img w-full h-full object-cover';
  // // const badge = document.getElementById('modalAvailBadge');
  // // badge.textContent = f.availability; badge.className = `text-xs font-bold px-3 py-1.5 rounded-full ${f.availClass}`;
  // document.getElementById('modalRatingScore').textContent = String(f.rating).replace('.', ',');
  // document.getElementById('modalStars').innerHTML = starRow(f.rating, '12px');
  // document.getElementById('modalReviewCount').textContent = `(${f.reviews} ulasan)`;
  document.getElementById('modalThumbs').innerHTML = f.images.map((img, i) => `<div class="thumb flex-shrink-0 ${i===0?'active':''}" onclick="switchModalImg(${i})" style="width:72px;height:52px;"><img src="${img}" alt="foto ${i+1}" class="w-full h-full object-cover"></div>`).join('');
  document.getElementById('modalName').textContent = f.name;
  document.getElementById('modalLocation').textContent = f.location;
  const specs = [
    { icon:'users', label:'Kapasitas', value:f.capacity },
    { icon:'maximize-2', label:'Luas', value:f.luas },
    { icon:'clock', label:'Operasional', value:f.operasional },
    { icon:'phone', label:'Kontak TU', value:f.kontak },
  ];
  document.getElementById('modalSpecs').innerHTML = specs.map(s => `<div class="flex items-start gap-2.5 bg-slate-50 rounded-xl p-3"><div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"><i data-lucide="${s.icon}" class="w-3.5 h-3.5 text-navy-600"></i></div><div><div class="text-slate-400 text-[10px] font-semibold uppercase tracking-wide">${s.label}</div><div class="text-navy-900 text-xs font-semibold mt-0.5 leading-snug">${s.value}</div></div></div>`).join('');
  document.getElementById('modalDesc').textContent = f.description;
  document.getElementById('modalFeatures').innerHTML = f.features.map(feat => `<span class="inline-flex items-center gap-1 bg-blue-50 text-navy-700 text-xs font-medium px-2.5 py-1 rounded-full border border-blue-100"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="#1a2f9b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>${feat}</span>`).join('');
  document.getElementById('modalRules').innerHTML = f.rules.map(rule => `<li class="flex items-start gap-2 text-slate-600 text-xs leading-relaxed"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#3355ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><polyline points="9 18 15 12 9 6"/></svg>${rule}</li>`).join('');
  document.getElementById('modalPriceOld').textContent = f.priceOld ? f.priceOld + '/hari' : '';
  document.getElementById('modalPrice').textContent = f.price;
  document.getElementById('facilityModal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  setTimeout(() => lucide.createIcons(), 30);
}

function switchModalImg(idx) {
  if (!activeModalFacility) return;
  const mainImg = document.getElementById('modalMainImg');
  mainImg.style.animation = 'none'; mainImg.offsetHeight;
  mainImg.style.animation = ''; mainImg.className = 'modal-img w-full h-full object-cover';
  mainImg.src = activeModalFacility.images[idx];
  document.querySelectorAll('#modalThumbs .thumb').forEach((th, i) => th.classList.toggle('active', i === idx));
}

function closeModal() {
  document.getElementById('facilityModal').classList.add('hidden');
  document.body.style.overflow = '';
  activeModalFacility = null;
}
function handleModalOverlayClick(e) { if (e.target === document.getElementById('facilityModal')) closeModal(); }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

function buildDemoAccounts() {
  const grid = document.getElementById('demoAccountsGrid'); if (!grid) return;
  grid.innerHTML = DEMO_ACCOUNTS.map(a => `
    <div class="bg-white rounded-xl border border-slate-100 p-5 card-hover">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 bg-gradient-to-br ${a.color} rounded-xl flex items-center justify-center"><i data-lucide="${a.icon}" class="w-5 h-5 text-white"></i></div>
        <div><div class="font-semibold text-navy-900 text-sm">${a.label}</div><div class="text-slate-400 text-xs">${a.desc}</div></div>
      </div>
      <div class="bg-slate-50 rounded-lg p-3 space-y-1.5 mb-3">
        <div class="flex justify-between text-xs"><span class="text-slate-400">Username:</span><span class="font-mono font-semibold text-navy-700">${a.username}</span></div>
        <div class="flex justify-between text-xs"><span class="text-slate-400">Password:</span><span class="font-mono font-semibold text-navy-700">${a.password}</span></div>
      </div>
      <a href="{{ route('login') }}"
        onclick="sessionStorage.setItem('prefill_u', '${a.username}'); sessionStorage.setItem('prefill_p', '${a.password}');"
        class="block w-full text-center py-2 text-sm font-semibold text-navy-600 bg-navy-50 hover:bg-navy-100 rounded-lg transition">
        Login Cepat →
      </a>
    </div>`).join('');
  lucide.createIcons();
}

// Form Handlers
function handlePengaduanSubmit(event) {
  event.preventDefault();
  alert('Terima kasih! Pengaduan Anda telah diterima. Kami akan menindaklanjuti dalam 5 hari kerja.');
  event.target.reset();
}

function handleSurveySubmit(event) {
  event.preventDefault();
  alert('Terima kasih atas feedback Anda! Masukan Anda sangat membantu kami untuk meningkatkan layanan.');
  event.target.reset();
}

buildCarousel();
buildDemoAccounts();
lucide.createIcons();

document.addEventListener("DOMContentLoaded", function () {
    // =========================
    // INIT CAROUSEL
    // =========================
    function initCarousel(carouselId, navId) {
        const carousel = document.getElementById(carouselId);
        if (!carousel) return; // cegah error jika tidak ada

        const slides = carousel.querySelectorAll('[class*="slide"]');
        const navContainer = navId ? document.getElementById(navId) : null;

        carousel.dataset.current = 0;
        carousel.dataset.total = slides.length;

        // Generate navigation dots
        if (navContainer) {
            const isModern = navContainer.classList.contains('carousel-modern-nav');
            const isMinimal = navContainer.classList.contains('carousel-minimal-nav');

            let dotClass = 'carousel-retro-dot';
            if (isModern) dotClass = 'carousel-modern-dot';
            if (isMinimal) dotClass = 'carousel-minimal-dot';

            navContainer.innerHTML = ""; // reset isi

            for (let i = 0; i < slides.length; i++) {
                const dot = document.createElement('button');
                dot.className = dotClass;
                if (i === 0) dot.classList.add('active');

                dot.addEventListener("click", function () {
                    goToSlide(carousel, i);
                });

                navContainer.appendChild(dot);
            }
        }

        updateCarousel(carousel);
    }

    // =========================
    // UPDATE CAROUSEL
    // =========================
    function updateCarousel(carousel) {
        if (!carousel) return;

        const slides = carousel.querySelectorAll('[class*="slide"]');
        const current = parseInt(carousel.dataset.current) || 0;

        // Update slide aktif
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === current);
        });

        // Update dot navigasi
        const navDots = carousel.querySelectorAll('[class*="dot"]');
        navDots.forEach((dot, index) => {
            dot.classList.toggle('active', index === current);
        });

        // Update progress bar (jika ada)
        const progress = carousel.querySelector('[class*="progress"]');
        if (progress && slides.length > 0) {
            progress.style.width = ((current + 1) / slides.length * 100) + '%';
        }
    }

    // =========================
    // NEXT SLIDE
    // =========================
    window.carouselNext = function (btn) {
        const carousel = btn.closest('[class*="carousel-"]');
        if (!carousel) return;

        const current = parseInt(carousel.dataset.current) || 0;
        const total = parseInt(carousel.dataset.total) || 0;

        carousel.dataset.current = (current + 1) % total;
        updateCarousel(carousel);
    };

    // =========================
    // PREV SLIDE
    // =========================
    window.carouselPrev = function (btn) {
        const carousel = btn.closest('[class*="carousel-"]');
        if (!carousel) return;

        const current = parseInt(carousel.dataset.current) || 0;
        const total = parseInt(carousel.dataset.total) || 0;

        carousel.dataset.current = (current - 1 + total) % total;
        updateCarousel(carousel);
    };

    // =========================
    // GO TO SLIDE
    // =========================
    function goToSlide(carousel, index) {
        if (!carousel) return;

        carousel.dataset.current = index;
        updateCarousel(carousel);
    }

    // =========================
    // INIT SEMUA CAROUSEL
    // =========================
    initCarousel('carousel1', 'nav1');
    initCarousel('carousel2', 'nav2');
    initCarousel('carousel3', 'nav3');

    // =========================
    // AUTOPLAY CAROUSEL 1
    // =========================
    setInterval(() => {
        const carousel = document.getElementById('carousel1');
        if (!carousel) return;

        const current = parseInt(carousel.dataset.current) || 0;
        const total = parseInt(carousel.dataset.total) || 0;

        if (total === 0) return;

        carousel.dataset.current = (current + 1) % total;
        updateCarousel(carousel);
    }, 5000);

    // Update function handleSurveySubmit
function handleSurveySubmit(event) {
  event.preventDefault();
  
  const form = document.getElementById('surveyForm');
  const submitBtn = document.getElementById('submitBtn');
  const submitText = submitBtn.querySelector('#submitText');
  
  // Show loading
  submitBtn.disabled = true;
  const originalText = submitBtn.innerHTML;
  submitBtn.innerHTML = '<span class="animate-spin">⏳</span> Mengirim...';
  
  // Submit via fetch untuk better UX
  fetch(form.action, {
    method: 'POST',
    body: new FormData(form),
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast('success', data.message);
      form.reset();
    } else {
      showToast('error', data.message);
    }
  })
  .catch(error => {
    showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
  })
  .finally(() => {
    submitBtn.disabled = false;
    submitBtn.innerHTML = originalText;
  });
}
});
</script>
</body>
</html>