<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Materi Menunggu Persetujuan</h2>

<?php if (session()->getFlashdata('message')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
<?php endif; ?>

<?php if (empty($materials)): ?>
    <div class="alert alert-info">Tidak ada materi yang menunggu persetujuan.</div>
<?php else: ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul Materi</th>
                <th>Course</th>
                <th>Tipe</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $m): ?>
                <tr>
                    <td><?= esc($m['title']) ?></td>
                    <td><?= esc($m['course_title']) ?></td>
                    <td><?= esc($m['type']) ?></td>
                    <td>
                        <a href="/admin/approve/<?= $m['course_id'] ?>" class="btn btn-success btn-sm">Setujui</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>
