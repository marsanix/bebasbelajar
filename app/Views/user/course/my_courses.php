<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Course Saya</h2>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session('message') ?></div>
<?php endif; ?>

<a href="<?= base_url('/my-course/create') ?>" class="btn btn-primary mb-4">+ Buat Course</a>

<?php if (empty($courses)): ?>
    <div class="alert alert-warning">Anda belum membuat course.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Thumbnail</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?= esc($course['title']) ?></td>
                    <td><?= esc($course['category_name']) ?></td>
                    <td>
                        <?php if ($course['is_published']): ?>
                            <span class="badge bg-success">Published</span>
                        <?php elseif ($course['is_approved']): ?>
                            <span class="badge bg-warning text-dark">Menunggu Publish</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Menunggu Approval</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?= base_url('uploads/images/' . $course['thumbnail']) ?>" width="80">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-info btn-sm">Lihat</a>
                        <?php if (! $course['is_approved']): ?>
                            <a href="<?= base_url('my-course/edit/' . $course['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>
