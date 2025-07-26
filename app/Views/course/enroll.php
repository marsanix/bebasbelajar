<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Gabung ke Course</h2>

<p>Apakah Anda yakin ingin bergabung dengan course: <strong><?= esc($course['title']) ?></strong>?</p>

<form method="post" action="<?= base_url('/course/enroll/' . $course['id']) ?>">
    <?= csrf_field() ?>
    <button type="submit" class="btn btn-primary">Gabung Course</button>
    <a href="<?= base_url('/') ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>
