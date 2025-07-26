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
                            <small class="text-muted">Kategori: <?= esc($course['category_name']) ?></small>
                        </p>
                        <p class="card-text"><?= esc(substr($course['description'], 0, 100)) ?>...</p>

                        <?php if (session('role') === 'admin' && $course['created_by'] != session('user_id')): ?>
                            <div class="mt-2 small">
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        data-id="<?= $course['id'] ?>" data-type="approved"
                                        <?= $course['is_approved'] ? 'checked' : '' ?>>
                                    <label class="form-check-label">Disetujui</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        data-id="<?= $course['id'] ?>" data-type="published"
                                        <?= $course['is_published'] ? 'checked' : '' ?>>
                                    <label class="form-check-label">Dipublikasikan</label>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mt-2">
                                <span class="badge bg-<?= $course['is_approved'] ? 'success' : 'warning' ?>">
                                    <?= $course['is_approved'] ? 'Disetujui' : 'Menunggu Persetujuan' ?>
                                </span>
                                <span class="badge bg-<?= $course['is_published'] ? 'primary' : 'secondary' ?>">
                                    <?= $course['is_published'] ? 'Dipublikasikan' : 'Belum Dipublish' ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-transparent border-0 mt-auto">
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
$(document).ready(function () {
    $('input.status-toggle').on('change', function () {
        alert('Status toggle clicked');
        const courseId = $(this).data('id');
        const type = $(this).data('type');
        const value = $(this).is(':checked') ? 1 : 0;

        let alertAjax = $('#alertAjax');

        $.ajax({
            url: "<?= base_url('admin/course/toggle-status') ?>",
            method: "POST",
            data: {
                course_id: courseId,
                field: type === 'approved' ? 'is_approved' : 'is_published',
                value: value,
                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
            },
            success: function (res) {

                if (res.status === 'success') {
                    alertAjax.html('<div class="alert alert-success">' + res.message + '</div>');
                } else {
                    alertAjax.html('<div class="alert alert-danger">' + res.message + '</div>');
                }   

                setTimeout(function() {
                    alertAjax.html('');
                }, 3000);

            },
            error: function () {
                alertAjax.html('<div class="alert alert-danger">Error saat mengirim permintaan.</div>');
                setTimeout(function() {
                    alertAjax.html('');
                }, 3000);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

