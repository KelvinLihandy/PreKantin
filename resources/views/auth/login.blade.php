<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk | PreKantin</title>

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
              <img src="{{ asset('images/PreKantinLogo.png') }}" 
                   alt="PreKantin Logo" 
                   class="img-fluid mb-4"
                   style="max-width: 200px;">

              <p class="small text-muted mb-0">
                Belum punya akun? 
                <a href="{{ url('/register') }}" class="text-primary fw-semibold text-decoration-none">Daftar</a>
              </p>
            </div>

            <div class="col-md-7 bg-white p-5">
              <h3 class="text-center mb-4 fw-semibold text-dark">Masuk</h3>

              <form>
                <div class="mb-3">
                  <input type="email" 
                         class="form-control form-control-lg border-0 text-white"
                         placeholder="Email"
                         style="background-color: #4191E8; caret-color: white;"
                         required>
                </div>

                <div class="mb-4 position-relative">
                  <input type="password" 
                         class="form-control form-control-lg border-0 text-white pe-5"
                         placeholder="Password"
                         style="background-color: #4191E8; caret-color: white;"
                         required>
                  <i class="bi bi-eye position-absolute top-50 end-0 translate-middle-y me-3 text-white"></i>
                </div>

                <button type="submit" 
                        class="btn btn-lg w-100 fw-semibold text-white border-0"
                        style="background-color: #FB8C30;">
                  Masuk
                </button>

                <div class="text-center mt-3">
                  <a href="#" class="btn btn-dark text-white fw-semibold rounded-pill px-4 py-1 small">
                    Lupa Password?
                  </a>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
