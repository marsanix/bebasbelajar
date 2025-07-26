<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Buat Course Baru</h2>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= is_array(session('error')) ? implode('<br>', session('error')) : session('error') ?>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="<?= old('title') ?>" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="4"><?= old('description') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Ajukan Course</button>
</form>

<?= $this->endSection() ?>
