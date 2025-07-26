<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Course Tersedia</h2>

<?php if (!empty($search)): ?>
  <div class="alert alert-info">Hasil pencarian untuk: <strong><?= esc($search) ?></strong></div>
<?php endif; ?>

<?php if (empty($courses)): ?>
  <div class="alert alert-warning">Tidak ada course ditemukan.</div>
<?php else: ?>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php foreach ($courses as $course): ?>
      <div class="col">
        <div class="card h-100">
          <?php if ($course['thumbnail']): ?>
            <img src="<?= base_url('uploads/public/images/' . $course['thumbnail']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
          <?php endif; ?>

            <div class="card-body">
                <h5 class="card-title"><?= esc($course['title']) ?></h5>
                
                <p class="mb-1">
                    <small class="text-muted">Kategori: <?= esc($course['category_name']) ?></small><br>
                    <small class="text-muted">Dibuat oleh: <?= esc($course['creator_name']) ?></small>
                </p>

                <p class="card-text"><?= esc(substr($course['description'], 0, 100)) ?>...</p>

            </div>

          <div class="card-footer bg-transparent border-0">
            <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-outline-primary w-100">Lihat Course</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
