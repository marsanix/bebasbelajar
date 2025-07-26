<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'BebasBelajar' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    #headerCarousel .carousel-item {
        height: 400px;
    }
    #headerCarousel img {
        object-fit: cover;
        height: 100%;
    }
    </style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid px-4">
    <a class="navbar-brand me-3" href="/">#BebasBelajar</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <!-- Form Search -->
      <form class="d-flex flex-grow-1 me-3" action="<?= base_url('/') ?>" method="get">
        <input class="form-control me-2 w-100" type="search" name="q" placeholder="Cari course..."
               aria-label="Search" value="<?= esc($_GET['q'] ?? '') ?>">
      </form>

      <!-- Right-side Menu -->
      <ul class="navbar-nav mb-2 mb-lg-0">
        <?php if (session()->get('isLoggedIn')): ?>
          <li class="nav-item">
            <a class="nav-link" href="/dashboard">Dashboard</a>
          </li>

          <?php if (session('role') === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="/admin/course">Courses</a>
            </li>
          <?php endif; ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <?= esc(session('name')) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= base_url('my-course') ?>">My Course</a></li>
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">My Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="/logout">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="/register">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


    <?php if (uri_string() === '' || uri_string() === 'home'): ?>
    <!-- Carousel Slide Here -->

        <!-- SLIDE HEADER -->
        <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="<?= base_url('uploads/images/slide1.jpg') ?>" class="d-block w-100" style="max-height: 400px; object-fit: cover;" alt="Slide 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Belajar Kapan Saja</h5>
                    <p>Akses ribuan materi pembelajaran secara gratis.</p>
                </div>
                </div>
                <div class="carousel-item">
                <img src="<?= base_url('uploads/images/slide2.jpg') ?>" class="d-block w-100" style="max-height: 400px; object-fit: cover;" alt="Slide 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Materi Multimedia</h5>
                    <p>Belajar dari video, ebook, dan interaktif lainnya.</p>
                </div>
                </div>
                <!-- Tambah slide lain jika perlu -->
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        
    <?php endif; ?>


<!-- CONTENT -->
<div class="container my-5">

    <div id="alertAjax"></div>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('message') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                <?php foreach ((array) session()->getFlashdata('error') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const toast = new bootstrap.Toast(document.getElementById('updateSuccessToast'));
        toast.show();
        });
    </script>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="updateSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">
            <?= session()->getFlashdata('message') ?>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
        </div>
    </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
    

    <!-- Modal: Profil Saya -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
            <form id="formProfile" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                <h5 class="modal-title">Profil Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                <div id="profileAlert"></div>

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" class="form-control" value="<?= esc(session('name')) ?>" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" value="<?= esc(session('email')) ?>" readonly>
                </div>

                <hr>
                <h6>Ubah Password (Opsional)</h6>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirm" class="form-control">
                </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
            </div>
        </div>
    </div>



</div>

<footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
  <div class="container text-center">
    <small>&copy; <?= date('Y') ?> BebasBelajar. All rights reserved.</small>
  </div>
</footer>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {
  $('#formProfile').on('submit', function (e) {
    e.preventDefault();

    const formData = $(this).serialize();

    $.ajax({
      url: '<?= base_url('profile/update') ?>',
      method: 'POST',
      data: formData,
      dataType: 'json',
      success: function (res) {
        let alertBox = $('#profileAlert');
        if (res.status === 'success') {
          alertBox.html('<div class="alert alert-success">' + res.message + '</div>');

          // Update nama di navbar dropdown
          $('.nav-link.dropdown-toggle').text(res.name ?? 'Profil');
          
          setTimeout(() => {
            $('#profileModal').modal('hide');
            alertBox.html('');
          }, 2000);
        } else {
          let errors = '';
          for (let key in res.errors) {
            errors += '<li>' + res.errors[key] + '</li>';
          }
          alertBox.html('<div class="alert alert-danger"><ul>' + errors + '</ul></div>');
        }
      },
      error: function () {
        $('#profileAlert').html('<div class="alert alert-danger">Terjadi kesalahan. Silakan coba lagi.</div>');
      }
    });
  });
});
</script>

<?= $this->renderSection('scripts') ?>

</body>
</html>
