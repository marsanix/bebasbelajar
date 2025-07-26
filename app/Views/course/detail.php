<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2><?= esc($course['title']) ?></h2>
<p><?= esc($course['description']) ?></p>
<p><span class="badge bg-secondary"><?= esc($course['category_name']) ?></span></p>

<hr>

<h4>Materi</h4>

<?php if ($isLoggedIn && ($isOwner || session()->get('role') == 'admin')): ?>
    <a href="<?= base_url('/material/upload/' . $course['id']) ?>" class="btn btn-success mb-3">+ Tambah Materi</a>
<?php endif; ?>

<?php if (! $isLoggedIn): ?>
    <div class="alert alert-warning">
        Anda harus <a href="<?= base_url('/login') ?>">login</a> untuk melihat materi.
    </div>

<?php elseif ((session('role') !== 'admin' && ! $isEnrolled) && !$isOwner): ?>
    <div class="alert alert-info">
        Anda belum mendaftar course ini.
        <form action="<?= base_url('course/enroll/' . $course['id']) ?>" method="post" class="d-inline">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success">Enroll Sekarang</button>
        </form>
    </div>

<?php elseif (empty($materials)): ?>
    <div class="alert alert-warning">Belum ada materi tersedia.</div>

<?php else: ?>

    <ul class="list-group">
        <?php foreach ($materials as $m): ?>
            <li class="list-group-item">

                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>
                            <a href="<?= base_url('material/view/' . $m['id']) ?>">
                                <?= esc($m['title']) ?>
                            </a>
                        </strong><br>
                    </div>

                    <?php if ($isLoggedIn && session('role') == 'admin'): ?>
                        <div>
                            <a href="<?= base_url('material/edit/' . $m['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?= base_url('material/delete/' . $m['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus materi ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!$isLoggedIn): ?>
                    <span class="text-muted">Login untuk melihat konten materi ini.</span>
                <?php else: ?>
                    
                    <?php if ($m['type'] === 'article'): ?>
                        <p><?= esc(substr($m['content'], 0, 100)) ?>...</p>
                    <?php elseif ($m['type'] === 'youtube' && $m['youtube_link']): ?>
                        <a href="<?= esc($m['youtube_link']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                            Tonton di YouTube
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('uploads/' . $m['file_path']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">Download / Lihat</a>
                    <?php endif; ?>

                <?php endif; ?>

            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?= $this->endSection() ?>
