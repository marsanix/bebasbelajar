<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2 class="mb-4">Dashboard</h2>

<a href="<?= base_url('/my-course/create') ?>" class="btn btn-primary mb-4">+ Buat Course</a>

<?php if (empty($courses)): ?>
    <div class="alert alert-info">Belum ada course yang tersedia.</div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php foreach ($courses as $course): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <?php if ($course['thumbnail']): ?>
                        <img src="<?= base_url('uploads/images/' . $course['thumbnail']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?= esc($course['title']) ?></h5>
                        
                        <p class="mb-1">
                            <small class="text-muted">Kategori: <?= esc($course['category_name']) ?></small><br>
                            <small class="text-muted">Dibuat oleh: <?= esc($course['creator_name']) ?></small>
                        </p>

                        <p class="card-text"><?= esc(substr($course['description'], 0, 100)) ?>...</p>

                        <?php if (session('user_id') == $course['created_by']): ?>
                            <div class="mt-2">
                                <span class="badge bg-<?= $course['is_approved'] ? 'success' : 'warning' ?>">
                                    <?= $course['is_approved'] ? 'Disetujui' : 'Menunggu Persetujuan' ?>
                                </span>

                                <?php if ($course['created_by'] == session('user_id') && $course['is_approved'] == 1): ?>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input toggle-publish" type="checkbox"
                                            data-course-id="<?= $course['id'] ?>"
                                            <?= $course['is_published'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">Status Publish</label>
                                    </div>
                                <?php else: ?>
                                <span class="badge bg-<?= $course['is_published'] ? 'primary' : 'secondary' ?>">
                                    <?= $course['is_published'] ? 'Dipublikasikan' : 'Belum Dipublish' ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="card-footer bg-transparent border-top-0 mt-auto">
                        <a href="<?= base_url('course/' . $course['id']) ?>" class="btn btn-outline-primary w-100">Lihat Course</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('.toggle-publish').on('change', function() {
        const courseId = $(this).data('course-id');
        const isPublished = $(this).is(':checked') ? 1 : 0;

        let alertAjax = $('#alertAjax');

        $.ajax({
            url: '<?= base_url('user/course/toggle-publish') ?>',
            type: 'POST',
            data: {
                course_id: courseId,
                is_published: isPublished,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function(response) {

                if (response.status === 'success') {
                    alertAjax.html('<div class="alert alert-success">' + response.message + '</div>');
                } else {
                    alertAjax.html('<div class="alert alert-danger">' + response.message + '</div>');
                }   

                setTimeout(function() {
                    alertAjax.html('');
                }, 3000);
            },
            error: function() {
                alertAjax.html('<div class="alert alert-danger">Gagal memperbarui status.</div>');
                setTimeout(function() {
                    alertAjax.html('');
                }, 3000);
            },
        });
    });
});
</script>

<?= $this->endSection() ?>