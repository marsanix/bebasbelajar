<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Manajemen Course</h2>
<a href="/admin/course/create" class="btn btn-success mb-3">Tambah Course</a>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Kategori</th> 
            <th>Thumbnail</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($courses as $course): ?>
            <tr>
                <td><a href="/course/<?= $course['id'] ?>"><?= esc($course['title']) ?></a></td>
                <td><?= esc($course['category_name'] ?? '-') ?></td>
                <td>
                    <?php if ($course['thumbnail']): ?>
                        <img src="<?= base_url('uploads/images/' . $course['thumbnail']) ?>" width="80">
                    <?php endif; ?>
                </td>
                <td>
                    <a href="/admin/course/edit/<?= $course['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/admin/course/delete/<?= $course['id'] ?>" onclick="return confirm('Hapus?')" class="btn btn-danger btn-sm">Hapus</a>
                    <a href="/admin/course/materials/<?= $course['id'] ?>" class="btn btn-info btn-sm">Materi</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
