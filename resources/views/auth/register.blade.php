<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Akun | PreKantin</title>

  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-md-11 col-sm-12">
        <div class="card shadow border-0 rounded-4 overflow-hidden position-relative">

          <a href="{{ url('/') }}" 
             class="btn btn-outline-primary position-absolute top-0 start-0 m-3 rounded d-flex align-items-center justify-content-center"
             style="width: 40px; height: 40px;">
            <i class="bi bi-arrow-left"></i>
          </a>

          <div class="row g-0 align-items-center">

  
            <div class="col-md-5 bg-white d-flex flex-column align-items-center justify-content-center p-4 text-center">

  
              <ul class="nav nav-tabs mb-4" id="roleTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active fw-semibold" 
                          id="student-tab"
                          data-bs-toggle="tab" 
                          data-bs-target="#student"
                          type="button" 
                          role="tab"
                          aria-controls="student" 
                          aria-selected="true">
                    Mahasiswa
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link fw-semibold" 
                          id="merchant-tab"
                          data-bs-toggle="tab" 
                          data-bs-target="#merchant"
                          type="button" 
                          role="tab"
                          aria-controls="merchant" 
                          aria-selected="false">
                    Merchant
                  </button>
                </li>
              </ul>

              <img src="{{ asset('images/PreKantinLogo.png') }}" 
                   alt="PreKantin Logo" 
                   class="img-fluid mb-4"
                   style="max-width: 200px;">

              <p class="small text-muted mb-0">
                Sudah punya akun? 
                <a href="{{ url('/login') }}" class="text-primary fw-semibold text-decoration-none">Masuk</a>
              </p>
            </div>

            <div class="col-md-7 bg-white p-5">
              <div class="tab-content" id="roleTabContent">

                <div class="tab-pane fade show active" id="student" role="tabpanel" aria-labelledby="student-tab">
                  <h3 class="text-center mb-4 fw-semibold text-dark">Daftar Akun Mahasiswa</h3>

                  <form>
                    <div class="mb-3">
                      <input type="text" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Nama Lengkap"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <div class="mb-3">
                      <input type="email" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Email"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <div class="mb-3 position-relative">
                      <input type="password" 
                             class="form-control form-control-lg border-0 text-white pe-5"
                             placeholder="Password"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                      <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 text-white"></i>
                    </div>

                    <div class="mb-4">
                      <input type="password" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Konfirmasi Password"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <button type="submit" 
                            class="btn btn-lg w-100 fw-semibold text-white border-0"
                            style="background-color: #FB8C30;">
                      Daftar
                    </button>
                  </form>
                </div>

                <div class="tab-pane fade" id="merchant" role="tabpanel" aria-labelledby="merchant-tab">
                  <h3 class="text-center mb-4 fw-semibold text-dark">Daftar Akun Merchant</h3>

                  <form>
                    <div class="mb-3">
                      <input type="text" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Nama Lengkap"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <div class="mb-3">
                      <input type="text" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Nama Toko"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <div class="mb-3">
                      <input type="email" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Email"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <div class="mb-3 position-relative">
                      <input type="password" 
                             class="form-control form-control-lg border-0 text-white pe-5"
                             placeholder="Password"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                      <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 text-white"></i>
                    </div>

                    <div class="mb-4">
                      <input type="password" 
                             class="form-control form-control-lg border-0 text-white"
                             placeholder="Konfirmasi Password"
                             style="background-color: #4191E8; caret-color: white;"
                             required>
                    </div>

                    <button type="submit" 
                            class="btn btn-lg w-100 fw-semibold text-white border-0"
                            style="background-color: #FB8C30;">
                      Daftar
                    </button>
                  </form>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

  <script>
    const tabs = document.querySelectorAll('.nav-link');
    tabs.forEach(tab => {
      tab.addEventListener('shown.bs.tab', () => {
        tabs.forEach(t => t.classList.remove('text-primary'));
        tab.classList.add('text-primary');
      });
    });
  </script>

</body>
</html>
