<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PreKantin | Cepat, Praktis, Lezat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-light bg-primary py-3">
    <div class="container">
      <a class="navbar-brand text-white fw-bold" href="#">
        <img src="{{ asset('images/HomeLogo.png') }}" alt="PreKantin" height="40" class="me-2">
        PreKantin
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Home</a></li>
          <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Kantin</a></li>
          <li class="nav-item"><a class="nav-link text-white fw-semibold" href="#">Tentang</a></li>
          <li class="nav-item"><a class="nav-link text-white fw-semibold" href="login">Masuk</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <section class="bg-primary text-white py-5">
    <div class="container">
      <div class="row align-items-center g-5">
        <div class="col-md-6 text-center text-md-start">
          <h1 class="fw-bold mb-3">Pesan Makanan Kantin Lebih Cepat & Praktis!</h1>
          <p class="lead mb-4">
            PreKantin membantu memesan makanan dan minuman dari kantin awal, agar istirahatmu nggak habis cuma untuk antre.
          </p>
          <a href="#" class="btn btn-warning btn-lg fw-semibold me-2">Pesan Sekarang</a>
          <a href="{{ url('/register') }}" class="btn btn-dark btn-lg fw-semibold">Daftar Jadi Merchant</a>
        </div>
        <div class="col-md-6 text-center">
          <img src="{{ asset('images/HomePic.png') }}" class="img-fluid rounded shadow mt-4 mt-md-0" alt="Kantin">
        </div>
      </div>
    </div>
  </section>

  <section class="bg-light py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Paling Banyak Dipesan</h3>
        <a href="#" class="btn btn-outline-primary fw-semibold">Lihat Menu Lain</a>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="{{ asset('images/BakmieAyamPolos.png') }}" class="card-img-top" alt="Bakmi">
            <div class="card-body">
              <h6 class="fw-semibold">Bakmi Keriting Ayam Biasa Polos</h6>
              <p class="mb-1 small text-muted">Oleh: Bakmi Effata</p>
              <p class="fw-bold text-dark">Rp 20.000</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="{{ asset('images/BakmieAyamPolos.png') }}" class="card-img-top" alt="Bakmi">
            <div class="card-body">
              <h6 class="fw-semibold">Bakmi Keriting Ayam Biasa Polos</h6>
              <p class="mb-1 small text-muted">Oleh: Bakmi Effata</p>
              <p class="fw-bold text-dark">Rp 20.000</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm">
            <img src="{{ asset('images/BakmieAyamPolos.png') }}" class="card-img-top" alt="Bakmi">
            <div class="card-body">
              <h6 class="fw-semibold">Bakmi Keriting Ayam Biasa Polos</h6>
              <p class="mb-1 small text-muted">Oleh: Bakmi Effata</p>
              <p class="fw-bold text-dark">Rp 20.000</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-primary text-white py-5 text-center">
  <div class="container">
    <h3 class="fw-bold mb-4">Kenapa Harus Menggunakan PreKantin?</h3>

    <ul class="nav nav-pills justify-content-center mb-4" id="roleTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link active fw-semibold px-4 py-2 text-dark bg-white shadow-sm"
                id="mahasiswa-tab" 
                data-bs-toggle="pill"
                data-bs-target="#mahasiswa" 
                type="button" 
                role="tab" 
                aria-controls="mahasiswa" 
                aria-selected="true">
                Mahasiswa
            </button>
        </li>
        <li class="nav-item ms-2" role="presentation">
            <button 
                class="nav-link fw-semibold px-4 py-2 text-dark bg-white"
                id="merchant-tab" 
                data-bs-toggle="pill"
                data-bs-target="#merchant" 
                type="button" 
                role="tab" 
                aria-controls="merchant" 
                aria-selected="false">
                Merchant
            </button>
        </li>
    </ul>


    <div class="tab-content" id="roleTabContent">

      <div class="tab-pane fade show active" id="mahasiswa" role="tabpanel" aria-labelledby="mahasiswa-tab">
        <div class="row justify-content-center align-items-center g-4">
          <div class="col-md-6">
            <div class="bg-white text-dark p-4 rounded-4 shadow-sm text-start">
              <h5 class="fw-bold text-warning">Waktu Istirahatmu Berharga, Jangan Habiskan Untuk Mengantre!</h5>
              <p class="mb-0 mt-2">
                Setiap detik di kampus berarti. Entah untuk belajar, berorganisasi, atau sekadar melepas penat bersama
                teman. Tapi, <strong>berapa banyak waktu yang terbuang hanya untuk antrean panjang di kantin?</strong>
              </p>
            </div>
          </div>
          <div class="col-md-5">
            <img src="{{ asset('images/home.png') }}" alt="Mahasiswa di kantin"
              class="img-fluid rounded-4 shadow-sm">
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="merchant" role="tabpanel" aria-labelledby="merchant-tab">
        <div class="row justify-content-center align-items-center g-4">
          <div class="col-md-6">
            <div class="bg-white text-dark p-4 rounded-4 shadow-sm text-start">
              <h5 class="fw-bold text-warning">Permudah Pelanggan, Maksimalkan Penjualanmu!</h5>
              <p class="mb-0 mt-2">
                Dengan PreKantin, kamu bisa menerima pesanan lebih cepat tanpa antrean panjang. Sistem kami membantu
                merchant mengatur order dengan efisien, biar jualan makin lancar dan pelanggan makin puas!
              </p>
            </div>
          </div>
          <div class="col-md-5">
            <img src="{{ asset('images/home.png') }}" alt="Merchant PreKantin"
              class="img-fluid rounded-4 shadow-sm">
          </div>
        </div>
      </div>

    </div>
  </div>
  </section>


  <footer class="bg-dark text-white py-5">
  <div class="container">
    <div class="row align-items-center text-center text-md-start">

      <div class="col-md-4 mb-4 mb-md-0 text-center text-md-start">
        <img src="{{ asset('images/HomeLogo.png') }}" 
             alt="PreKantin" 
             class="img-fluid mb-2"
             style="max-height: 80px;">
      </div>

      <div class="col-md-4 text-center">
        <h4 class="fw-bold text-warning mb-2">PreKantin</h4>
        <p class="mb-0 fs-5 fw-semibold">Cepat, Praktis, Lezat.</p>
      </div>


     <div class="col-md-4 text-center text-md-end">
        <p class="fw-semibold mb-2">Hubungi Kami</p>

        <p class="small mb-1">
          <i class="bi bi-telephone"></i> (+62) 0821 2571 4778
        </p>
        <p class="small mb-1">
          <i class="bi bi-geo-alt"></i> Binus University, Kampus Anggrek
        </p>
        <p class="small mb-1">
          <i class="bi bi-envelope"></i> frandy.s@prekantin.com
        </p>

        <p class="small mt-2 fw-semibold" style="color:#4191E8;">
          @ 2025 PreKantin All Rights Reserved
        </p>
      </div>


    </div>
  </div>
</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
