<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Edit Materi</h2>

<form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="<?= esc($material['title']) ?>" required>
    </div>

    <div class="mb-3">
        <label>Tipe Konten</label>
        <select name="type" id="typeSelect" class="form-select" required>
            <option value="">-- Pilih Tipe --</option>
            <?php foreach (['article', 'youtube', 'video', 'audio', 'image', 'ebook'] as $type): ?>
                <option value="<?= $type ?>" <?= $material['type'] === $type ? 'selected' : '' ?>><?= ucfirst($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Field konten untuk artikel -->
    <div class="mb-3" id="contentField">
        <label>Isi Artikel</label>
        <textarea name="content" class="form-control" rows="5"><?= esc($material['content']) ?></textarea>
    </div>

    <!-- Field link youtube -->
    <div class="mb-3 <?= $material['type'] !== 'youtube' ? 'd-none' : '' ?>" id="youtubeField">
        <label>Link YouTube</label>
        <input type="text" name="youtube_link" class="form-control" value="<?= esc($material['youtube_link']) ?>">
    </div>

    <!-- Upload file -->
    <div class="mb-3 <?= in_array($material['type'], ['video', 'audio', 'image', 'ebook']) ? '' : 'd-none' ?>" id="fileField">
        <label>Upload File (kosongkan jika tidak ingin diganti)</label>
        <input type="file" name="file" class="form-control">

        <?php if (!empty($material['file_path'])): ?>
            <div class="mt-2">
                <strong>File Saat Ini:</strong><br>
                <a href="<?= base_url('uploads/' . $material['file_path']) ?>" target="_blank">
                    <?= esc(basename($material['file_path'])) ?>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="<?= base_url('course/' . $material['course_id']) ?>" class="btn btn-secondary">Batal</a>
</form>

<!-- jQuery CDN -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $('#typeSelect').on('change', function () {
        let type = $(this).val();

        $('#youtubeField, #fileField').addClass('d-none');

        if (type === 'youtube') {
            $('#youtubeField').removeClass('d-none');
        } else if (['video', 'audio', 'image', 'ebook'].includes(type)) {
            $('#fileField').removeClass('d-none');
        }
    });
</script>

<?= $this->endSection() ?>
