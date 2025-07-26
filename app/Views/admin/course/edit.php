<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Edit Course</h2>

<form method="post" enctype="multipart/form-data" action="/admin/course/update/<?= $course['id'] ?>">
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="<?= esc($course['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control"><?= esc($course['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label>Thumbnail</label>
        <input type="file" name="thumbnail" class="form-control">
        <?php if ($course['thumbnail']): ?>
            <img src="<?= base_url('uploads/images/' . $course['thumbnail']) ?>" width="120">
        <?php endif; ?>
    </div>

    <div class="mb-3">
        <label for="category_id">Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= isset($course) && $course['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Status Approve</label>
        <select name="is_approved" class="form-select">
            <option value="0" <?= $course['is_approved'] == 0 ? 'selected' : '' ?>>Belum Disetujui</option>
            <option value="1" <?= $course['is_approved'] == 1 ? 'selected' : '' ?>>Disetujui</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Status Publikasi</label>
        <select name="is_published" class="form-select">
            <option value="0" <?= $course['is_published'] == 0 ? 'selected' : '' ?>>Tidak Dipublikasikan</option>
            <option value="1" <?= $course['is_published'] == 1 ? 'selected' : '' ?>>Dipublikasikan</option>
        </select>
    </div>

    <button class="btn btn-success">Update</button>
</form>

<?= $this->endSection() ?>
