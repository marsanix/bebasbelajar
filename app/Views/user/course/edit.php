<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Edit Course Saya</h2>

<form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label>Kategori</label>
        <select name="category_id" class="form-select" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $course['category_id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="<?= esc($course['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="5"><?= esc($course['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control">
        <?php if ($course['thumbnail']): ?>
            <img src="<?= base_url('uploads/images/' . $course['thumbnail']) ?>" width="120" class="mt-2">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('my-course') ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>
