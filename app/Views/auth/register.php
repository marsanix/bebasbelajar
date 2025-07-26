<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">Daftar Akun Baru</h2>

        <!-- Tampilkan pesan sukses -->
        <?php if (session()->getFlashdata('auth_message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('auth_message') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tampilkan pesan error -->
        <?php if (session()->getFlashdata('auth_error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('auth_error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="/register">
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" value="<?= old('name') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="<?= old('email') ?>" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>

        <p class="mt-3 text-center">Sudah punya akun? <a href="/login">Login</a></p>
    </div>
</div>

<?= $this->endSection() ?>
