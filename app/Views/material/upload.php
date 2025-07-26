<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2>Upload Materi Baru</h2>
<form method="post" enctype="multipart/form-data" class="mt-4">
    <div class="mb-3">
        <label>Judul Materi</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Tipe Konten</label>
        <select name="type" id="typeSelect" class="form-select" required>
            <option value="article">Artikel</option>
            <option value="video">Video</option>
            <option value="youtube">YouTube</option>
            <option value="audio">Audio</option>
            <option value="image">Gambar</option>
            <option value="ebook">E-Book</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Isi Konten (untuk artikel)</label>
        <textarea name="content" class="form-control" rows="5"></textarea>
    </div>

    <!-- Field untuk YouTube -->
    <div class="mb-3 d-none" id="youtubeField">
        <label>Link YouTube</label>
        <input type="text" name="youtube_link" class="form-control" placeholder="https://www.youtube.com/watch?v=xxxxx">
    </div>

    <!-- Field untuk upload file -->
    <div class="mb-3 d-none" id="fileField">
        <label>Upload File</label>
        <input type="file" name="file" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Upload Materi</button>
    <a href="/dashboard" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- jQuery CDN -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $('#typeSelect').on('change', function () {
        let selected = $(this).val();

        $('#youtubeField, #fileField').addClass('d-none');

        if (selected === 'youtube') {
            $('#youtubeField').removeClass('d-none');
        } else if (['video', 'audio', 'image', 'ebook'].includes(selected)) {
            $('#fileField').removeClass('d-none');
        }
    });
</script>
<?= $this->endSection() ?>