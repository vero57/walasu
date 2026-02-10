<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E Walas SMKN 1 Cibinong- Walas</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="images/logokampak.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

  <style>
        /* Kotak pesan */
        .alert {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            z-index: 9999;
            text-align: center;
            font-size: 16px;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideDown 0.5s ease-out;
            box-sizing: border-box;
            max-height: 60px; /* Menentukan tinggi kotak pesan agar tidak terlalu panjang */
            overflow: hidden;

            /* Flexbox untuk menyejajarkan teks di tengah */
            display: flex;
            justify-content: center; /* Mengatur teks ke tengah secara horizontal */
            align-items: center; /* Mengatur teks ke tengah secara vertikal */
        }

        .alert-danger {
            background-color: #e74c3c; /* Merah */
        }

        .alert-success {
            background-color: #2ecc71; /* Hijau */
        }

        /* Animasi slide down */
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        /* Animasi slide up untuk saat pesan hilang */
        @keyframes slideUp {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-100%);
            }
        }
    </style>
</head>

<body class="index-page">
     <!-- Tampilkan pesan error -->
     @if (session('error'))
        <div class="alert alert-danger" id="alertError">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <!-- Tampilkan pesan success -->
    @if (session('success'))
        <div class="alert alert-success" id="alertSuccess">
            <p>{{ session('success') }}</p>
        </div>
    @endif

 <header id="header" class="header d-flex align-items-center fixed-top">
  <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

    <a href="/walaspage" class="logo d-flex align-items-center me-auto me-xl-0">
      <!-- Uncomment the line below if you also wish to use an image logo -->
      <!-- <img src="assets/img/logo.png" alt=""> -->
      <h1 class="sitename">E - Walas</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <!-- Menampilkan ikon user dan informasi walas yang sedang login -->
  <div class="user-info d-flex align-items-center">
              @if(session()->has('walas_id'))
                  <i class="bi bi-person-circle text-primary me-2" style="font-size: 24px;"></i>  <!-- Icon User dengan warna biru -->

                  <!-- Tautkan nama walas ke /userprofile -->
                  <a href="/profilewalas" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Profile">
                      <span>{{ $walas->nama }}</span>  <!-- Nama Walas yang sedang login -->
                  </a>
              @endif
      <form action="{{ route('logoutwalas') }}" method="POST" class="ms-3">
          @csrf
          <button type="submit" class="btn-getstarted" data-bs-toggle="tooltip" data-bs-placement="top" title="Keluar dari Akun Anda">Logout</button>
      </form>
</div>

    </div>

  </div>
</header>


  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-gear-fill me-2"></i>
                    Aman, Tertib, Unggul, Religius
              </div>

              <h1 class="mb-4">
               Selamat datang Walas <br>
                <span class="accent-text">SMK Negeri 1 Cibinong</span>
              </h1>

              <p class="mb-4 mb-md-5">
               Sudah Siap Beroperasi Hari Ini?
              </p>

              <div class="hero-buttons">
                <a href="https://youtu.be/9auMDyT3BOA" class="btn btn-link tutorial-btn mt-2 mt-sm-0 glightbox" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Vidio">
                    <i class="bi bi-play-circle me-1"></i>
                    Tutorial Penggunaan Website
                </a>
            </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="assets/img/illustration-1.webp" alt="Hero Image" class="img-fluid">

            </div>
          </div>
        </div>

        <div class="row stats-row gy-4 mt-5 justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="500">
  <div class="col-lg-3 col-md-6">
    <div class="stat-item text-center">
      <div class="stat-icon">
        <i class="bi bi-trophy"></i>
      </div>
    <div class="stat-content">
        <h4>Siswa</h4>
        <p class="mb-0">
            <a href="/siswadata" data-bs-toggle="tooltip" data-bs-placement="top" title="Management Data Siswa">
                Kelola Data Siswa di Sini
            </a>
        </p>
    </div>

    </div>
  </div>
  <div class="col-lg-3 col-md-6">
    <div class="stat-item text-center">
      <div class="stat-icon">
        <i class="bi bi-briefcase"></i>
      </div>
      <div class="stat-content">
        <h4>Administrasi Walas</h4>
        <p class="mb-0">
            <a href="/adminwalas" data-bs-toggle="tooltip" data-bs-placement="top" title="Management data Administrasi">Kelola Administrasi Walas di Sini</a>
        </p>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6">
    <div class="stat-item text-center">
      <div class="stat-icon">
        <i class="bi bi-card-checklist"></i>
      </div>
      <div class="stat-content">
        <h4>Menu Absensi</h4>
        <p class="mb-0">
            <a href="/absensi" data-bs-toggle="tooltip" data-bs-placement="top" title="Management data Administrasi">Kelola Data Absen di Sini</a>
        </p>
      </div>
    </div>
  </div>
</div>

      </div>

    </section><!-- /Hero Section -->

</main>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SIJA SMKN 1 Cibinong</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
        window.onload = function() {
            // Cek jika ada pesan error
            var errorAlert = document.getElementById('alertError');
            var successAlert = document.getElementById('alertSuccess');

            // Jika ada pesan error, sembunyikan setelah 2 detik dengan animasi
            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.animation = 'slideUp 0.5s ease-out'; // Animasi naik
                    setTimeout(function() {
                        errorAlert.style.display = 'none'; // Sembunyikan setelah animasi selesai
                    }, 500); // Durasi animasi
                }, 2000); // Tunda selama 2 detik sebelum animasi
            }

            // Jika ada pesan success, sembunyikan setelah 2 detik dengan animasi
            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.animation = 'slideUp 0.5s ease-out'; // Animasi naik
                    setTimeout(function() {
                        successAlert.style.display = 'none'; // Sembunyikan setelah animasi selesai
                    }, 500); // Durasi animasi
                }, 2000); // Tunda selama 2 detik sebelum animasi
            }
        };
    </script>

</body>

</html>
