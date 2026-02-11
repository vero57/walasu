<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E Walas SMKN 1 Cibinong - Absensi</title>
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

   <!-- Unicons CSS -->
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

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
            max-height: 60px;
            overflow: hidden;

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .alert-danger {
            background-color: #e74c3c;
        }

        .alert-success {
            background-color: #2ecc71;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(0);
            }
            to {
                transform: translateY(-100%);
            }
        }

        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 14px rgba(0, 0, 255, 0.2);
            padding: 20px;
        }

        .modal-body form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .modal-body form .mb-3 {
            grid-column: span 1;
        }

        .modal-body form .mb-3:last-child {
            grid-column: span 2;
        }

        .modal-footer {
            justify-content: flex-start;
            padding-right: 100px;
        }

        .modal-footer button {
            width: 100px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .modal-footer .btn-secondary:hover {
            background-color: #adb5bd;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .modal-footer .btn-success {
            background-color: #0d6efd;
            border: none;
        }

        .modal-footer .btn-success:hover {
            background-color: #70b0ff;
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.4);
        }

        .modal-body input,
        .modal-body select {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding: 8px;
        }

        .modal-body input[type="file"] {
            padding: 5px;
        }

        .modal-dialog {
            max-width: 800px;
            width: 90%;
        }

        .d-flex-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ms-3 {
            margin-left: 1rem;
        }

        .me-2 {
            margin-right: 0.5rem;
        }

        .input-box {
          position: relative;
          height: 55px;
          max-width: 900px;
          width: 100%;
          background: #fff;
          margin: 0 20px;
          border-radius: 8px;
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }
        .input-box i,
        .input-box .button {
          position: absolute;
          top: 50%;
          transform: translateY(-50%);
        }
        .input-box i {
          left: 20px;
          font-size: 30px;
          color: #707070;
        }
        .input-box input {
          height: 100%;
          width: 100%;
          outline: none;
          font-size: 18px;
          font-weight: 400;
          border: none;
          padding: 0 155px 0 65px;
          background-color: transparent;
        }
        .input-box .button {
          right: 25px;
          font-size: 15px;
          font-weight: 300;
          color: #fff;
          border: none;
          padding: 12px 30px;
          border-radius: 6px;
          background-color:  #0d83fd;
          cursor: pointer;
        }
        .input-box .button:active {
          transform: translateY(-50%) scale(0.98);
        }

        .button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: 2px solid #007bff;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 90%;
                margin: auto;
            }
        }

        .input-box input {
            width: 100%;
        }

        .input-box .button {
            white-space: nowrap;
        }

        .d-flex.gap-2 {
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .d-flex.justify-content-center.flex-wrap {
                flex-direction: column;
                align-items: center;
            }
        }

        .modal-body form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .modal-body .row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .modal-body .col-md-6 {
            width: 100%;
        }

        @media (min-width: 768px) {
            .modal-body .col-md-6 {
                width: 48%;
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

      <a href="/walaspage" class="logo d-flex align-items-center me-auto me-xl-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Kembali ke Halaman Home">
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
                <i class="bi bi-person-circle text-primary me-2" style="font-size: 24px;"></i>

                <!-- Tautkan nama walas ke /userprofile -->
                <a href="/profilewalas" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Profile">
                    <span>{{ $walas->nama ?? 'Walas' }}</span>
                </a>
            @endif
            <form action="{{ route('logoutwalas') }}" method="POST" class="ms-3" data-bs-toggle="tooltip" data-bs-placement="top" title="Keluar dari Akun Anda">
                @csrf
                <button type="submit" class="btn-getstarted">Logout</button>
            </form>
        </div>
    </div>
  </header>

<main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section">
        <div class="starter-section container" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-2 align-items-center">
                <!-- Header Title -->
                <div class="col-12 mb-4">
                    <h2 class="font-weight-bold">Data Absensi</h2>
                    <hr class="my-3">
                </div>

                <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <!-- Tombol Unggah Data & Tambah Data -->
                    <!-- <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#uploadAbsensiModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Unggah Data Melalui Excel">
                            <i class="bi bi-cloud-upload"></i> Unggah Data
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAbsensiModal" data-bs-toggle="tooltip" data-bs-placement="top" title="Tambah Data Manual">
                            <i class="bi bi-plus"></i> Tambah
                        </button>
                    </div> -->

                    <!-- Form Cari Absensi -->
                    <form action="{{ url('absensi_search') }}" method="GET" class="d-flex align-items-center">
                        <div class="input-box d-flex align-items-center">
                            <i class="uil uil-search"></i>
                            <input type="text" name="keyword" placeholder="Cari Absensi..." value="{{ old('keyword', $keyword ?? '') }}" required />
                            <button class="button" type="submit">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Jumlah Total Absensi -->
            <div class="row mt-3">
                <div class="col-12 text-center text-md-end">
                    <span class="text-muted">
                        Jumlah Total: <strong>0 Data</strong>
                    </span>
                </div>
            </div>

            <!-- Container Table dengan Scroll jika terlalu lebar -->
            <div class="d-flex align-items-center justify-content-start">
                    <div class="container">
                        <h2>Daftar Absensi Kelas: {{ $namaKelas }}</h2>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dataAbsensi as $index => $absen)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $absen['student_name'] }}</td>
                                        <td>{{ $absen['date'] }}</td>
                                        <td>
                                            <span class="badge text-black">{{ $absen['status_name'] }}</span>
                                        </td>
                                        <td>
                                            @if($absen['photo_url'])
                                                <img src="{{ $absen['photo_url'] }}" width="50">
                                            @else
                                                No Photo
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data absensi untuk kelas XI DKV 1.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
            </div>

<!-- Modal Unggah Data Absensi -->
<div class="modal fade" id="uploadAbsensiModal" tabindex="-1" aria-labelledby="uploadAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadAbsensiModalLabel">Unggah Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/absensi-import" method="post" enctype="multipart/form-data" id="uploadAbsensiForm">
                @csrf
                <div class="modal-body">
                    <!-- Tombol Download Template -->
                    <div class="mb-3">
                        <a href="{{-- {{ route('absensi.download-template') }} --}}" class="btn btn-primary btn-sm" target="_blank">Download Template Excel</a>
                    </div>

                    <!-- Form Input -->
                    <div class="mb-3">
                        <label for="fileUploadAbsensi" class="form-label">Pilih File (CSV, Excel)</label>
                        <input type="file" name="file" class="form-control" id="fileUploadAbsensi" accept=".csv, .xlsx" required>
                    </div>

                    <!-- Keterangan Proses Mengunggah (Awalnya Disembunyikan) -->
                    <div id="uploadingMessage" class="alert alert-info d-none">
                        <strong>⏳ Sedang mengunggah data...</strong> Mohon tunggu.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

                    <!-- Button Unggah -->
                    <button type="submit" class="btn btn-primary" id="uploadAbsensiButton">Unggah</button>

                    <!-- Button Mengunggah + Spinner (Awalnya Disembunyikan) -->
                    <button id="loadingSpinnerAbsensi" class="btn btn-primary d-none" type="button" disabled>
                        <span>Mengunggah...</span>
                        <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Loading -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">⏳ <strong>Sedang mengunggah data...</strong> Mohon tunggu.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data Absensi -->
<div class="modal fade" id="addAbsensiModal" tabindex="-1" aria-labelledby="addAbsensiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAbsensiModalLabel">Tambah Data Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{-- {{ route('absensi.store') }} --}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="container">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label for="tanggalAbsensi" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggalAbsensi" name="tanggal" required>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="siswaId" class="form-label">Siswa</label>
                                <select class="form-select" id="siswaId" name="siswa_id" required>
                                    <option selected disabled>Pilih Siswa</option>
                                    @if(isset($siswa))
                                        @foreach ($siswa as $item)
                                            <option value="{{ $item->id }}">{{ $item->siswa_nama }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="statusAbsensi" class="form-label">Status</label>
                                <select class="form-select" id="statusAbsensi" name="status" required>
                                    <option selected disabled>Pilih Status</option>
                                    <option value="Hadir">Hadir</option>
                                    <option value="Sakit">Sakit</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Alpa">Alpa</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-12">
                                <label for="keteranganAbsensi" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keteranganAbsensi" name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between flex-wrap">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

        </div>
    </section>

</main>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SIJA SMKN 1 Cibinong</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
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
            var errorAlert = document.getElementById('alertError');
            var successAlert = document.getElementById('alertSuccess');

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.animation = 'slideUp 0.5s ease-out';
                    setTimeout(function() {
                        errorAlert.style.display = 'none';
                    }, 500);
                }, 2000);
            }

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.animation = 'slideUp 0.5s ease-out';
                    setTimeout(function() {
                        successAlert.style.display = 'none';
                    }, 500);
                }, 2000);
            }
        };
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("uploadAbsensiForm").addEventListener("submit", function(event) {
                console.log("⏳ Form submission started...");

                let uploadButton = document.getElementById("uploadAbsensiButton");
                let loadingSpinner = document.getElementById("loadingSpinnerAbsensi");
                let uploadingMessage = document.getElementById("uploadingMessage");

                if (uploadButton && loadingSpinner && uploadingMessage) {
                    uploadButton.classList.add("d-none");
                    loadingSpinner.classList.remove("d-none");
                    uploadingMessage.classList.remove("d-none");
                    console.log("✅ Spinner & keterangan proses muncul!");
                } else {
                    console.log("❌ ERROR: Elemen tidak ditemukan!");
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("uploadAbsensiForm").addEventListener("submit", function (event) {
                console.log("⏳ Unggah dimulai...");

                let loadingModal = new bootstrap.Modal(document.getElementById("loadingModal"));
                loadingModal.show();
            });
        });
    </script>

</body>

</html>
