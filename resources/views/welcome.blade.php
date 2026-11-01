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
          <a href="#panduan"   class="text-blue-100 hover:text-white text-sm font-medium transition">Panduan</a>
          {{-- <a href="#pengaduan-survey" class="text-blue-100 hover:text-white text-sm font-medium transition">Pengaduan</a> --}}
          <a href="#kontak"    class="text-blue-100 hover:text-white text-sm font-medium transition">Kontak</a>
        </div>
        <div class="flex items-center gap-3">
          <a href="{{ route('login', [], false) }}"    class="text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-white/10 transition">Masuk</a>
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
      <a href="#panduan"   class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Panduan</a>
      {{-- <a href="#pengaduan-survey" class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Pengaduan</a> --}}
      <a href="#kontak"    class="block py-2 text-blue-100 text-sm" onclick="document.getElementById('mobileMenu').classList.add('hidden')">Kontak</a>
      <div class="flex gap-2 mt-3 pt-3 border-t border-white/10">
        <a href="{{ route('login', [], false) }}"    class="flex-1 text-center text-white text-sm font-semibold py-2 rounded-lg border border-white/20 hover:bg-white/10 transition">Masuk</a>
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
          <a href="{{ route('login', [], false) }}" class="btn-primary text-white font-semibold px-8 py-3.5 rounded-xl flex items-center justify-center gap-2 text-base">
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
                          @php
                              $heroPhotos = collect();
                              $facSource = $facilities ?? \App\Models\Gedung::getBerandaFacilities();
                              if (!empty($facSource)) {
                                  foreach ($facSource as $facItem) {
                                      if (!empty($facItem['images']) && is_array($facItem['images'])) {
                                          foreach ($facItem['images'] as $imgUrl) {
                                              if (!empty($imgUrl)) {
                                                  $heroPhotos->push($imgUrl);
                                                  if ($heroPhotos->count() >= 6) break 2;
                                              }
                                          }
                                      }
                                  }
                              }
                              if ($heroPhotos->isEmpty()) {
                                  $heroPhotos = collect([
                                      asset('storage/fasilitas/kantor_utama.jpeg'),
                                      asset('storage/fasilitas/kantor_ponuwa.jpeg'),
                                      asset('storage/fasilitas/gedung_aula.jpeg'),
                                      asset('storage/fasilitas/tinelo_1.jpeg')
                                  ]);
                              }
                          @endphp
                          @foreach($heroPhotos as $hIdx => $hPhoto)
                              <div class="carousel-modern-slide {{ $hIdx === 0 ? 'active' : '' }}">
                                  <img src="{{ $hPhoto }}" class="w-full h-full object-cover" alt="Fasilitas BPMP">
                              </div>
                          @endforeach
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
      <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 md:gap-5">
        
        <div class="stat-card glass rounded-2xl p-5 text-center">
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-300"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </div>
            <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">{{ number_format($totalAset ?? 0) }}</div>
            <div class="text-blue-200 text-xs md:text-sm font-medium">Total Aset Tetap</div>
        </div>

        <div class="stat-card glass rounded-2xl p-5 text-center">
            <div class="w-12 h-12 bg-indigo-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-300"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5V19A9 3 0 0 0 21 19V5"/><path d="M3 12A9 3 0 0 0 21 12"/></svg>
            </div>
            <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">{{ number_format($totalPersediaan ?? 0) }}</div>
            <div class="text-blue-200 text-xs md:text-sm font-medium">Total Persediaan</div>
        </div>

        <div class="stat-card glass rounded-2xl p-5 text-center">
            <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-300"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">Rp {{ $formattedNilaiAset }}M</div>
            <div class="text-blue-200 text-xs md:text-sm font-medium">Nilai Aset Terkelola</div>
        </div>

        <div class="stat-card glass rounded-2xl p-5 text-center">
            <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-yellow-300"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
            </div>
            <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">{{ $totalTransaksiBulanIni }}</div>
            <div class="text-blue-200 text-xs md:text-sm font-medium">Transaksi Bulan Ini</div>
        </div>

        <div class="stat-card glass rounded-2xl p-5 text-center col-span-2 lg:col-span-1">
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-300"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">{{ $persentaseKondisiBaik }}%</div>
            <div class="text-blue-200 text-xs md:text-sm font-medium">Kondisi Baik</div>
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

  <!-- ==================== SECTION PANDUAN PENGGUNAAN ==================== -->
  <section id="panduan" class="py-20 bg-slate-100/70 border-t border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      
      <!-- Header Section -->
      <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 bg-blue-100/80 text-blue-700 rounded-full px-4 py-1.5 mb-4 text-sm font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
          <span>Pusat Bantuan &amp; Panduan</span>
        </div>
        <h2 class="text-3xl md:text-4xl font-extrabold text-navy-900 tracking-tight mb-4">
          Panduan Penggunaan &amp; Fungsi Menu
        </h2>
        <p class="text-slate-600 text-base md:text-lg leading-relaxed">
          Pelajari alur operasional, tata cara pengajuan layanan, dan fungsi setiap menu pada SIPANDU BPMP Provinsi Gorontalo.
        </p>
      </div>

      <!-- Main Category Tabs -->
      <div class="flex justify-center mb-8">
        <div class="inline-flex p-1.5 bg-white rounded-2xl shadow-sm border border-slate-200/80 gap-1 sm:gap-2">
          <button type="button" onclick="switchGuideMainTab('roles')" id="mainTabBtn-roles" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 bg-navy-900 text-white shadow-md flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Panduan Peran (Role)</span>
          </button>
          <button type="button" onclick="switchGuideMainTab('sop')" id="mainTabBtn-sop" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-navy-900 hover:bg-slate-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span>Alur Kerja (SOP)</span>
          </button>
          <button type="button" onclick="switchGuideMainTab('akses')" id="mainTabBtn-akses" class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-navy-900 hover:bg-slate-100 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span>Akses &amp; Login</span>
          </button>
        </div>
      </div>

      <!-- ================= PANDUAN PERAN (ROLES) CONTAINER ================= -->
      <div id="guideContent-roles" class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-lg">
        
        <!-- Role Pills Selector -->
        <div class="mb-8">
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3 text-center sm:text-left">Pilih Peran Pengguna:</p>
          <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
            <button type="button" onclick="switchRoleGuide('pegawai')" id="roleBtn-pegawai" class="role-pill active px-4 py-2 rounded-xl text-xs md:text-sm font-bold border transition-all duration-200 flex items-center gap-2 bg-blue-50 text-blue-700 border-blue-200 shadow-sm">
              <span class="w-2 h-2 rounded-full bg-blue-600"></span> Pegawai
            </button>
            <button type="button" onclick="switchRoleGuide('kasubag')" id="roleBtn-kasubag" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-indigo-600"></span> Kasubag Umum
            </button>
            <button type="button" onclick="switchRoleGuide('adminasettetap')" id="roleBtn-adminasettetap" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-orange-500"></span> Admin Aset Tetap
            </button>
            <button type="button" onclick="switchRoleGuide('adminpersediaan')" id="roleBtn-adminpersediaan" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span> Admin Persediaan
            </button>
            <button type="button" onclick="switchRoleGuide('adminsarpras')" id="roleBtn-adminsarpras" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-cyan-600"></span> Admin Sarpras
            </button>
            <button type="button" onclick="switchRoleGuide('kepalabpmp')" id="roleBtn-kepalabpmp" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-purple-600"></span> Kepala BPMP
            </button>
            <button type="button" onclick="switchRoleGuide('superadmin')" id="roleBtn-superadmin" class="role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-rose-600"></span> Operator
            </button>
          </div>
        </div>

        <!-- 1. DETAIL PEGAWAI -->
        <div id="roleDetail-pegawai" class="role-detail-box space-y-6">
          <div class="bg-blue-50/60 rounded-2xl p-5 border border-blue-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Pegawai BPMP Gorontalo</h3>
              <p class="text-slate-600 text-sm mt-1">Digunakan oleh seluruh ASN / Pegawai BPMP untuk mengajukan peminjaman aset tetap &amp; kendaraan dinas, permintaan ATK/persediaan, peminjaman barang lainnya per kode unit, serta mutasi inventaris.</p>
            </div>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-blue-600">📦</span> Peminjaman Barang &amp; Kendaraan
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Mengajukan peminjaman peralatan kantor (terurut sesuai NUP) serta mobil/motor dinas untuk kegiatan operasional dan perjalanan dinas.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-indigo-600">↩️</span> Pengembalian Barang &amp; Kendaraan
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Melaporkan pengembalian aset tetap maupun armada dinas disertai kondisi terkini, catatan pemakaian, dan unggah foto bukti.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-emerald-600">📝</span> Permintaan Persediaan (ATK)
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Mengajukan kebutuhan bahan habis pakai kantor (kertas, tinta, alat tulis, bahan kebersihan) untuk unit kerja masing-masing.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-amber-600">⚡</span> Pinjam &amp; Kembali Barang Lainnya
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Meminjam peralatan pendukung (kabel roll/cokrol, steker, adaptor) dengan memilih <strong>Kode Unit fisik (Kode A, B, C, dst.)</strong> serta melaporkan pengembaliannya.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-orange-600">📍</span> Ajuan Mutasi Inventaris
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Mengajukan perpindahan lokasi ruangan atau pengalihan penanggung jawab atas aset tetap di unit kerja.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm flex items-center gap-2 mb-1">
                <span class="text-slate-600">⚙️</span> Pengaturan Akun &amp; Notifikasi WA
              </h4>
              <p class="text-slate-500 text-xs leading-relaxed">Memperbarui profil, nomor WhatsApp untuk notifikasi persetujuan otomatis, tanda tangan digital, dan kata sandi.</p>
            </div>
          </div>
        </div>

        <!-- 2. DETAIL KASUBAG -->
        <div id="roleDetail-kasubag" class="role-detail-box hidden space-y-6">
          <div class="bg-indigo-50/60 rounded-2xl p-5 border border-indigo-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Kasubag Umum</h3>
              <p class="text-slate-600 text-sm mt-1">Pejabat otorisator persetujuan (*Approval*) berjenjang untuk peminjaman barang &amp; kendaraan dinas, sekaligus pengawas monitoring penyaluran persediaan dan aset.</p>
            </div>
          </div>

          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm mb-1 text-indigo-700">Persetujuan Peminjaman Barang</h4>
              <p class="text-slate-500 text-xs leading-relaxed">Memvalidasi permohonan pinjam aset tetap yang telah diverifikasi Admin Aset (Setujui / Tolak dengan catatan).</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm mb-1 text-indigo-700">Persetujuan Kendaraan Dinas</h4>
              <p class="text-slate-500 text-xs leading-relaxed">Memberikan otorisasi pemakaian armada dinas berdasarkan jadwal kegiatan dan urgensi tugas pegawai.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm mb-1 text-indigo-700">Monitoring Terpadu</h4>
              <p class="text-slate-500 text-xs leading-relaxed">Memantau riwayat Permintaan Persediaan, status peminjaman Barang/Aset Tetap, dan pergerakan Kendaraan secara real-time.</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50">
              <h4 class="font-bold text-navy-900 text-sm mb-1 text-indigo-700">Laporan Transaksi Keluar</h4>
              <p class="text-slate-500 text-xs leading-relaxed">Meninjau rekapitulasi pengeluaran barang persediaan gudang dan mengekspor laporan resmi PDF.</p>
            </div>
          </div>
        </div>

        <!-- 3. DETAIL ADMIN ASET TETAP -->
        <div id="roleDetail-adminasettetap" class="role-detail-box hidden space-y-6">
          <div class="bg-orange-50/60 rounded-2xl p-5 border border-orange-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-orange-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Admin Aset Tetap</h3>
              <p class="text-slate-600 text-sm mt-1">Mengelola siklus hidup lengkap Barang Milik Negara (BMN) mulai dari pendataan master aset, transaksi masuk/keluar, mutasi ruangan, hingga layanan peminjaman &amp; pengembalian.</p>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Data Aset Tetap</strong>
              <span class="text-slate-500 leading-relaxed">Katalog BMN, NUP berurutan, merk/tipe, kondisi fisik, nilai perolehan, Import Excel &amp; cetak barcode.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Transaksi &amp; Mutasi Barang</strong>
              <span class="text-slate-500 leading-relaxed">Pencatatan pengadaan masuk, pengeluaran/penghapusan aset, mutasi lokasi, &amp; proses Info Ajuan Mutasi pegawai.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Peminjaman &amp; Pengembalian</strong>
              <span class="text-slate-500 leading-relaxed">Verifikasi awal pinjam barang/kendaraan ke Kasubag, verifikasi pengembalian fisik, &amp; cetak BAST / Surat Kembali.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Laporan &amp; Statistik</strong>
              <span class="text-slate-500 leading-relaxed">Ringkasan analitik kondisi BMN, perhitungan nilai aset, serta ekspor Laporan Transaksi Keluar (PDF/Excel).</span>
            </div>
          </div>
        </div>

        <!-- 4. DETAIL ADMIN PERSEDIAAN -->
        <div id="roleDetail-adminpersediaan" class="role-detail-box hidden space-y-6">
          <div class="bg-emerald-50/60 rounded-2xl p-5 border border-emerald-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16.5 9.4 7.55 4.24"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Admin Persediaan</h3>
              <p class="text-slate-600 text-sm mt-1">Mengelola stok barang habis pakai (ATK &amp; operasional) dengan riwayat harga pengadaan, melayani permintaan pegawai, serta mengelola peminjaman <strong>Barang Lainnya</strong> berbasis Kode Unit.</p>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Data Persediaan &amp; Riwayat Harga</strong>
              <span class="text-slate-500 leading-relaxed">Katalog barang habis pakai, Import Excel, pemantauan stok kritis, dan daftar riwayat harga per transaksi masuk.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Transaksi Masuk, Keluar &amp; Permintaan</strong>
              <span class="text-slate-500 leading-relaxed">Input barang masuk multi-item, pengeluaran stok gudang, serta verifikasi &amp; penyaluran permintaan ATK pegawai.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Barang Lainnya (Kode Unit)</strong>
              <span class="text-slate-500 leading-relaxed">Kelola alat pendukung berkode unit otomatis (A, B, C...) yang bisa diedit manual, konfirmasi kode unit pinjam, &amp; verifikasi kembali + cetak Berita Acara PDF.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Laporan &amp; Statistik Logistik</strong>
              <span class="text-slate-500 leading-relaxed">Cetak rekap Laporan Transaksi Masuk, Transaksi Keluar, dan Laporan Permintaan Persediaan unit kerja (PDF).@if(config('features.show_stok_opname')) Termasuk Stok Opname Bulanan.@endif</span>
            </div>
          </div>
        </div>

        <!-- 5. DETAIL ADMIN SARPRAS -->
        <div id="roleDetail-adminsarpras" class="role-detail-box hidden space-y-6">
          <div class="bg-cyan-50/60 rounded-2xl p-5 border border-cyan-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-cyan-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Admin Sarpras</h3>
              <p class="text-slate-600 text-sm mt-1">Mengelola data gedung &amp; etalase fasilitas beranda, serta pencatatan, tindak lanjut perbaikan, dan pelaporan kerusakan sarana prasarana.</p>
            </div>
          </div>

          <div class="grid sm:grid-cols-3 gap-3 text-xs">
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Data Gedung &amp; Etalase</strong>
              <span class="text-slate-500 leading-relaxed">Kelola master gedung, aula, ruang kelas, asrama/mess, spesifikasi kapasitas, serta galeri fasilitas di beranda.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Data Kerusakan Sarpras</strong>
              <span class="text-slate-500 leading-relaxed">Pencatatan kerusakan fisik bangunan/fasilitas, tingkat keparahan, estimasi penanganan, dan progres perbaikan.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Laporan Kerusakan</strong>
              <span class="text-slate-500 leading-relaxed">Rekapitulasi riwayat pemeliharaan dan pencetakan dokumen Laporan Kerusakan Sarpras (PDF).</span>
            </div>
          </div>
        </div>

        <!-- 6. DETAIL KEPALA BPMP -->
        <div id="roleDetail-kepalabpmp" class="role-detail-box hidden space-y-6">
          <div class="bg-purple-50/60 rounded-2xl p-5 border border-purple-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Kepala BPMP</h3>
              <p class="text-slate-600 text-sm mt-1">Pimpinan eksekutif untuk pengawasan menyeluruh, pemantauan neraca kondisi BMN, dan pengunduhan laporan rekapitulasi resmi instansi.</p>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 text-sm block mb-1">📊 Dashboard &amp; Analitik Eksekutif</strong>
              <span class="text-slate-500 leading-relaxed">Pantauan grafik kondisi aset tetap (Baik, Rusak Ringan, Rusak Berat), total nilai aset terkelola, stok persediaan, dan aktivitas transaksi secara real-time.</span>
            </div>
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 text-sm block mb-1">📥 Laporan BMN &amp; Unduh Rekapitulasi PDF</strong>
              <span class="text-slate-500 leading-relaxed">Akses cepat untuk mengunduh Laporan Persediaan, Laporan Aset Tetap, Laporan Sarpras, hingga Laporan Gabungan Lengkap ber-kop resmi (PDF).</span>
            </div>
          </div>
        </div>

        <!-- 7. DETAIL OPERATOR / SUPER ADMIN -->
        <div id="roleDetail-superadmin" class="role-detail-box hidden space-y-6">
          <div class="bg-rose-50/60 rounded-2xl p-5 border border-rose-100 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-navy-900">Peran: Operator (Super Admin)</h3>
              <p class="text-slate-600 text-sm mt-1">Administrator utama sistem yang mengelola akun pengguna lintas peran, struktur unit kerja, pencadangan (*backup*) database, dan pengaturan fitur sistem.</p>
            </div>
          </div>

          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Kelola Pengguna</strong>
              <span class="text-slate-500 leading-relaxed">Tambah, ubah peran, reset kata sandi, dan aktif/nonaktifkan akun pegawai maupun admin pengelola.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Kelola Unit Kerja</strong>
              <span class="text-slate-500 leading-relaxed">Pengaturan daftar tim kerja / subbagian resmi di lingkungan BPMP Provinsi Gorontalo.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Backup &amp; Pemeliharaan Data</strong>
              <span class="text-slate-500 leading-relaxed">Unduh backup database penuh atau per modul (SQL/JSON) serta pembersihan data terproteksi kata sandi.</span>
            </div>
            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50">
              <strong class="text-navy-900 block mb-1">Pengaturan Fitur Sistem</strong>
              <span class="text-slate-500 leading-relaxed">Kontrol aktivasi modul lanjutan seperti metode antrean batch harga FIFO pada Persediaan.</span>
            </div>
          </div>
        </div>

      </div>

      <!-- ================= ALUR SOP CONTAINER ================= -->
      <div id="guideContent-sop" class="hidden bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-lg">
        <div class="grid md:grid-cols-3 gap-6">
          
          <!-- SOP 1 -->
          <div class="border border-slate-200 rounded-2xl p-5 bg-gradient-to-b from-white to-blue-50/30 flex flex-col">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center mb-4">1</div>
            <h4 class="font-bold text-navy-900 text-base mb-2">Peminjaman &amp; Pengembalian Aset / Kendaraan</h4>
            <ol class="text-slate-600 text-xs space-y-2 list-decimal list-inside leading-relaxed flex-1">
              <li>Pegawai memilih barang (urut NUP) atau kendaraan dinas pada menu peminjaman.</li>
              <li>Admin Aset Tetap memverifikasi ketersediaan fisik dan meneruskan ke Kasubag Umum.</li>
              <li>Kasubag Umum menyetujui (*Approve*) dan notifikasi WhatsApp terkirim otomatis.</li>
              <li>Selesai dinas, Pegawai mengisi form <strong>Pengembalian</strong> beserta kondisi &amp; foto bukti.</li>
              <li>Admin Aset memverifikasi laporan kembali dan mencetak Berita Acara Pengembalian (PDF).</li>
            </ol>
          </div>

          <!-- SOP 2 -->
          <div class="border border-slate-200 rounded-2xl p-5 bg-gradient-to-b from-white to-emerald-50/30 flex flex-col">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center mb-4">2</div>
            <h4 class="font-bold text-navy-900 text-base mb-2">Permintaan Persediaan (ATK &amp; Habis Pakai)</h4>
            <ol class="text-slate-600 text-xs space-y-2 list-decimal list-inside leading-relaxed flex-1">
              <li>Pegawai memilih item persediaan dan mengisi jumlah kebutuhan unit kerja.</li>
              <li>Admin Persediaan memeriksa ketersediaan stok gudang pada menu Permintaan Persediaan.</li>
              <li>Saat disetujui &amp; disalurkan, stok gudang otomatis terpotong dan tercatat sebagai Transaksi Keluar.</li>
              <li>Kasubag Umum &amp; Kepala BPMP dapat memantau rekap penyaluran secara real-time maupun PDF.</li>
            </ol>
          </div>

          <!-- SOP 3 -->
          <div class="border border-slate-200 rounded-2xl p-5 bg-gradient-to-b from-white to-amber-50/30 flex flex-col">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 font-bold flex items-center justify-center mb-4">3</div>
            <h4 class="font-bold text-navy-900 text-base mb-2">Peminjaman Barang Lainnya (Kode Unit)</h4>
            <ol class="text-slate-600 text-xs space-y-2 list-decimal list-inside leading-relaxed flex-1">
              <li>Pegawai memilih perlengkapan (misal <em>Kabel Roll</em>) lalu mengklik <strong>Kode Unit</strong> yang tersedia (misal <code>Kode B</code>).</li>
              <li>Admin Persediaan menyetujui pengajuan sekaligus memverifikasi (atau menyesuaikan) Kode Unit fisik yang diserahkan.</li>
              <li>Status <code>Kode B</code> terkunci sebagai <strong>Dipinjam</strong> agar tidak bentrok dengan peminjam lain.</li>
              <li>Pegawai melaporkan pengembalian, Admin memverifikasi kondisi, Cetak Bukti PDF tersedia, dan <code>Kode B</code> kembali <strong>Tersedia</strong>.</li>
            </ol>
          </div>

        </div>
      </div>

      <!-- ================= AKSES & LOGIN CONTAINER ================= -->
      <div id="guideContent-akses" class="hidden bg-white rounded-3xl p-6 md:p-8 border border-slate-200/80 shadow-lg">
        <div class="grid md:grid-cols-2 gap-8 items-center">
          <div class="space-y-4">
            <h3 class="text-2xl font-bold text-navy-900">Cara Masuk &amp; Pendaftaran Akun Pegawai</h3>
            <p class="text-slate-600 text-sm leading-relaxed">
              SIPANDU dirancang khusus untuk internal lingkungan BPMP Provinsi Gorontalo dan secara otomatis mengarahkan setiap pengguna ke dashboard sesuai perannya setelah berhasil masuk.
            </p>
            <div class="space-y-3 text-sm">
              <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</span>
                <p class="text-slate-600"><strong class="text-navy-900">Pegawai &amp; Admin Terdaftar:</strong> Klik tombol <strong>"Masuk"</strong> di bagian atas halaman, lalu masukkan <em>Username</em> dan <em>Password</em> akun Anda.</p>
              </div>
              <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</span>
                <div class="text-slate-600">
                  <p><strong class="text-navy-900">Registrasi Pegawai Baru:</strong> Klik tombol <strong>"Daftar"</strong>, lengkapi Nama Lengkap, NIP/NIK, pilih Unit Kerja, serta buat Username dan Kata Sandi.</p>
                  <div class="mt-1.5 inline-block rounded-lg bg-amber-50 border border-amber-200 px-3 py-1.5 text-xs text-amber-800">
                    💡 <strong>Catatan Password:</strong> Wajib memuat minimal <strong>8 karakter</strong> dan minimal <strong>1 huruf besar/kapital (A–Z)</strong>, contoh: <code>Sipandu2026</code>.
                  </div>
                </div>
              </div>
              <div class="flex items-start gap-3">
                <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</span>
                <p class="text-slate-600"><strong class="text-navy-900">Profil, WhatsApp &amp; Keamanan:</strong> Lengkapi nomor WhatsApp dan tanda tangan digital di menu <strong>Pengaturan Akun</strong> untuk mendukung notifikasi otomatis serta dokumen BAST.</p>
              </div>
            </div>
            <div class="pt-2 flex gap-3 flex-wrap">
              <a href="{{ route('login', [], false) }}" class="btn-primary text-white text-sm font-semibold px-6 py-2.5 rounded-xl inline-flex items-center gap-2">
                <span>Buka Halaman Masuk</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
              </a>
              <a href="{{ route('register') }}" class="border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-semibold px-6 py-2.5 rounded-xl inline-flex items-center gap-2">
                <span>Daftar Akun Pegawai</span>
              </a>
            </div>
          </div>
          <div class="bg-gradient-to-br from-navy-900 to-blue-900 text-white p-6 rounded-2xl shadow-xl">
            <h4 class="text-lg font-bold text-blue-200 mb-3 flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
              <span>Butuh Bantuan Lebih Lanjut?</span>
            </h4>
            <p class="text-blue-100 text-xs leading-relaxed mb-4">
              Jika Anda mengalami kendala aktivasi akun pegawai, lupa kata sandi, penyesuaian unit kerja, atau memerlukan bantuan teknis penggunaan aplikasi, silakan hubungi Operator / Tim BMN &amp; Umum BPMP Provinsi Gorontalo.
            </p>
            <div class="border-t border-white/10 pt-3 text-xs text-blue-200 space-y-1">
              <div>📞 <strong>Hotline Layanan BMN &amp; Sarpras:</strong> 081313266661</div>
              <div>📧 <strong>Email:</strong> bpmpgorontalo@kemdikdasmen.go.id</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <script>
    function switchGuideMainTab(tab) {
      // Toggle Tab Buttons
      const tabs = ['roles', 'sop', 'akses'];
      tabs.forEach(t => {
        const btn = document.getElementById('mainTabBtn-' + t);
        const content = document.getElementById('guideContent-' + t);
        if (t === tab) {
          btn.className = 'px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 bg-navy-900 text-white shadow-md flex items-center gap-2';
          content.classList.remove('hidden');
        } else {
          btn.className = 'px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-navy-900 hover:bg-slate-100 flex items-center gap-2';
          content.classList.add('hidden');
        }
      });
    }

    function switchRoleGuide(role) {
      const roles = ['pegawai', 'kasubag', 'adminasettetap', 'adminpersediaan', 'adminsarpras', 'kepalabpmp', 'superadmin'];
      roles.forEach(r => {
        const btn = document.getElementById('roleBtn-' + r);
        const detail = document.getElementById('roleDetail-' + r);
        if (!btn || !detail) return;
        if (r === role) {
          btn.className = 'role-pill active px-4 py-2 rounded-xl text-xs md:text-sm font-bold border transition-all duration-200 flex items-center gap-2 bg-blue-50 text-blue-700 border-blue-200 shadow-sm';
          detail.classList.remove('hidden');
        } else {
          btn.className = 'role-pill px-4 py-2 rounded-xl text-xs md:text-sm font-semibold border border-slate-200 text-slate-600 hover:bg-slate-50 transition-all duration-200 flex items-center gap-2';
          detail.classList.add('hidden');
        }
      });
    }
  </script>
  <!-- ==================== END SECTION PANDUAN PENGGUNAAN ==================== -->

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
                  <button onclick="closeModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">Kembali</button>
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

const FACILITIES = {{ Illuminate\Support\Js::from($facilities ?? \App\Models\Gedung::getBerandaFacilities()) }};

const BASE_FACILITY_CHIPS = [
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

// Deteksi kategori dinamis jika ada penambahan kategori baru dari Admin Sarpras
const knownChipIds = new Set(BASE_FACILITY_CHIPS.map(c => c.id));
const dynamicChips = [];
(FACILITIES || []).forEach(f => {
  if (f && f.category && !knownChipIds.has(f.category)) {
    knownChipIds.add(f.category);
    const label = f.category.replace(/[_-]+/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    dynamicChips.push({ id: f.category, label });
  }
});
const FACILITY_CHIPS = [...BASE_FACILITY_CHIPS, ...dynamicChips];

@if(config('demo.enabled'))
const DEMO_ACCOUNTS = [
  { username:'Operator',       password:'super123',      label:'Super Admin',         color:'from-red-500 to-rose-600',     icon:'shield',    desc:'Akses penuh ke seluruh sistem' },
  { username:'kepalabpmp',     password:'kepala123',     label:'Kepala BPMP',            color:'from-purple-500 to-indigo-600',icon:'crown',     desc:'Dashboard eksekutif & persetujuan' },
  { username:'kasubag',        password:'kasubag123',    label:'Kasubag TU',             color:'from-indigo-500 to-blue-600',  icon:'briefcase', desc:'Verifikasi & koordinasi BMN' },
  { username:'adminpersediaan',password:'persediaan123', label:'Admin Persediaan',      color:'from-emerald-500 to-green-600',icon:'package',   desc:'Kelola stok & barang habis pakai' },
  { username:'adminsarpras',   password:'sarpras123',    label:'Admin Sarana Prasarana',color:'from-cyan-500 to-teal-600',   icon:'building',  desc:'Monitor gedung & fasilitas' },
  { username:'adminasettetap', password:'aset123',       label:'Admin Aset Tetap',      color:'from-amber-500 to-yellow-600', icon:'landmark',  desc:'Inventarisasi aset tetap' },
  { username:'pegawai',        password:'pegawai123',    label:'Pegawai',               color:'from-slate-500 to-gray-600',   icon:'user',      desc:'Peminjaman & riwayat BMN' },
];

@else
const DEMO_ACCOUNTS = [];
@endif

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

function escapeFacility(value) {
  const el = document.createElement('span');
  el.textContent = String(value ?? '');
  return el.innerHTML.replaceAll('"', '&quot;').replaceAll("'", '&#39;');
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
    const imgs = (f.images && Array.isArray(f.images) && f.images.length > 0) ? f.images : ['{{ asset("storage/fasilitas/gedung_aula.jpeg") }}'];
    const featList = (f.features && Array.isArray(f.features)) ? f.features : [];
    const opText = (f.operasional || '08.00 WITA').split(' ')[0];
    return `<article class="facility-card flex-shrink-0 w-[260px] sm:w-[280px] snap-start rounded-2xl bg-white shadow-md border border-slate-100/80 overflow-hidden card-hover flex flex-col" onclick="openModal(${globalIdx})" title="Klik untuk lihat detail">
      <div class="relative overflow-hidden" style="aspect-ratio:4/3;">
        <img src="${imgs[0]}" alt="${escapeFacility(f.name)}" class="card-img w-full h-full object-cover" loading="lazy">
        <div class="card-overlay absolute inset-0 flex flex-col justify-end p-3"><div class="view-detail-btn inline-flex items-center gap-1.5 bg-white/90 backdrop-blur text-navy-800 text-xs font-bold px-3 py-1.5 rounded-full self-start shadow"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>Lihat Detail</div></div>
        ${imgs.length > 1 ? `<div class="absolute top-2 right-2 flex gap-0.5">${imgs.map((_,i)=>`<div class="w-1.5 h-1.5 rounded-full ${i===0?'bg-white':'bg-white/50'}"></div>`).join('')}</div>` : ''}
      </div>
      <div class="p-3.5 flex flex-col flex-1 space-y-1.5">
      <h3 class="font-bold text-navy-900 text-sm leading-snug line-clamp-2">${escapeFacility(f.name)}</h3>
      
      <p class="text-slate-500 text-xs flex items-center gap-1">
        📍 <span class="truncate">${escapeFacility(f.location)}</span>
      </p>

      <div class="space-y-1 mb-3">
        <p class="text-xs font-medium text-slate-700 mb-1">Fasilitas:</p>
        <div class="flex flex-wrap gap-1">
          ${featList.slice(0,4).map(feat => `<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">${escapeFacility(feat)}</span>`).join('')}
        </div>
      </div>
      
      <div class="flex items-center justify-between gap-2 text-xs text-slate-500">
        <span>📏 ${escapeFacility(f.luas)}</span>
        <span>👥 ${escapeFacility(f.capacity)}</span>
        <span>🕒 ${escapeFacility(opText)}</span>
      </div>
      <p class="text-emerald-600 text-xs font-medium flex items-center gap-1 pt-1 border-t border-slate-200/50 mt-auto">
        📞 ${escapeFacility(f.kontak)}
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
  const imgs = (f.images && Array.isArray(f.images) && f.images.length > 0) ? f.images : ['{{ asset("storage/fasilitas/gedung_aula.jpeg") }}'];
  const mainImg = document.getElementById('modalMainImg');
  mainImg.src = imgs[0]; mainImg.alt = f.name || 'Fasilitas';
  mainImg.className = 'modal-img w-full h-full object-cover';
  document.getElementById('modalThumbs').innerHTML = imgs.map((img, i) => `<div class="thumb flex-shrink-0 ${i===0?'active':''}" onclick="switchModalImg(${i})" style="width:72px;height:52px;"><img src="${img}" alt="foto ${i+1}" class="w-full h-full object-cover"></div>`).join('');
  document.getElementById('modalName').textContent = f.name || '';
  document.getElementById('modalLocation').textContent = f.location || '';
  const specs = [
    { icon:'users', label:'Kapasitas', value:f.capacity || '-' },
    { icon:'maximize-2', label:'Luas', value:f.luas || '-' },
    { icon:'clock', label:'Operasional', value:f.operasional || '08.00 - 16.00 WITA' },
    { icon:'phone', label:'Kontak TU', value:f.kontak || '(0435) 821-555' },
  ];
  document.getElementById('modalSpecs').innerHTML = specs.map(s => `<div class="flex items-start gap-2.5 bg-slate-50 rounded-xl p-3"><div class="w-7 h-7 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"><i data-lucide="${s.icon}" class="w-3.5 h-3.5 text-navy-600"></i></div><div><div class="text-slate-400 text-[10px] font-semibold uppercase tracking-wide">${s.label}</div><div class="text-navy-900 text-xs font-semibold mt-0.5 leading-snug">${escapeFacility(s.value)}</div></div></div>`).join('');
  document.getElementById('modalDesc').textContent = f.description || '';
  const featList = (f.features && Array.isArray(f.features)) ? f.features : [];
  document.getElementById('modalFeatures').innerHTML = featList.map(feat => `<span class="inline-flex items-center gap-1 bg-blue-50 text-navy-700 text-xs font-medium px-2.5 py-1 rounded-full border border-blue-100"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="#1a2f9b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>${escapeFacility(feat)}</span>`).join('');
  const ruleList = (f.rules && Array.isArray(f.rules)) ? f.rules : [];
  document.getElementById('modalRules').innerHTML = ruleList.map(rule => `<li class="flex items-start gap-2 text-slate-600 text-xs leading-relaxed"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="#3355ff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><polyline points="9 18 15 12 9 6"/></svg>${escapeFacility(rule)}</li>`).join('');
  document.getElementById('modalPriceOld').textContent = f.priceOld ? f.priceOld + '/hari' : '';
  document.getElementById('modalPrice').textContent = f.price || 'Gratis / Khusus Internal';
  document.getElementById('facilityModal').classList.remove('hidden');
  document.body.style.overflow = 'hidden';
  setTimeout(() => lucide.createIcons(), 30);
}

function switchModalImg(idx) {
  if (!activeModalFacility) return;
  const imgs = (activeModalFacility.images && Array.isArray(activeModalFacility.images) && activeModalFacility.images.length > 0)
    ? activeModalFacility.images
    : ['{{ asset("storage/fasilitas/gedung_aula.jpeg") }}'];
  const mainImg = document.getElementById('modalMainImg');
  mainImg.style.animation = 'none'; mainImg.offsetHeight;
  mainImg.style.animation = ''; mainImg.className = 'modal-img w-full h-full object-cover';
  mainImg.src = imgs[idx] || imgs[0];
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
      <a href="{{ route('login', [], false) }}"
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
