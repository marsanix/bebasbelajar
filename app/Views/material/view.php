<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<h2><?= esc($material['title']) ?></h2>

<p>
    <strong>Tipe:</strong> <?= esc($material['type']) ?><br>
    <strong>Dibuat oleh:</strong> <?= esc($material['creator_name'] ?? 'User') ?>
</p>

<hr>


<?php if ($material['type'] === 'youtube'): ?>
    <div class="ratio ratio-16x9">
        <iframe src="https://www.youtube.com/embed/<?= getYouTubeId($material['youtube_link']) ?>" frameborder="0" allowfullscreen></iframe>
    </div>

<?php elseif (in_array($material['type'], ['video', 'audio', 'image', 'ebook'])): ?>
    <?php
        $fileUrl = base_url('uploads/' . $material['file_path']);
    ?>
    <?php if ($material['type'] === 'image'): ?>
        <img src="<?= $fileUrl ?>" class="img-fluid">
    <?php elseif ($material['type'] === 'audio'): ?>
        <audio controls src="<?= $fileUrl ?>"></audio>
    <?php elseif ($material['type'] === 'video'): ?>
        <video controls width="100%" src="<?= $fileUrl ?>"></video>
    <?php elseif ($material['type'] === 'ebook'): ?>
        <a href="<?= $fileUrl ?>" target="_blank" class="btn btn-primary">Buka E-Book</a>
    <?php endif; ?>
<?php endif; ?>

<div class="mt-3"><?= nl2br(esc($material['content'])) ?></div>

<hr>
<h4>Komentar</h4>

<?php if (! session('isLoggedIn')): ?>
    <div class="alert alert-info">Silakan <a href="<?= base_url('login') ?>">login</a> untuk memberikan komentar.</div>
<?php else: ?>

    <?php if (! empty($comments)): ?>
            <div class="mb-3">
                <?php renderComments($comments); ?>
            </div>
        <?php endif; ?>

    <div id="commentFormContainer" class="mt-3">
        

        <form id="commentForm" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="parent_id" id="parent_id" value="">
            <div class="mb-3">
                <textarea name="comment" class="form-control" rows="3" placeholder="Tulis komentar..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
            <button type="button" id="cancelReply" class="btn btn-secondary ms-2 d-none">Batal</button>
        </form>
        <div id="commentMessage" class="mt-2"></div>
    </div>

    <div id="defaultCommentPosition" class="mt-3"></div>

<?php endif; ?>


<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function () {
    // Kirim komentar via AJAX
    $('#commentForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const url = "<?= base_url('material/comment/' . $material['id']) ?>";
        const data = form.serialize();

        $.post(url, data)
            .done(function (response) {
                $('#commentMessage').html(`<div class="alert alert-success">${response.message}</div>`);
                form.trigger("reset");
                $('#parent_id').val('');
                setTimeout(() => location.reload(), 1000); // reload untuk ambil komentar terbaru
            })
            .fail(function (xhr) {
                const res = xhr.responseJSON;
                let errors = '<ul>';
                $.each(res.error || {}, function (key, val) {
                    errors += `<li>${val}</li>`;
                });
                errors += '</ul>';
                $('#commentMessage').html(`<div class="alert alert-danger">${errors}</div>`);
            });
    });

    // Saat klik balas
    $('.reply-link').on('click', function (e) {
        e.preventDefault();
        const commentId = $(this).data('id');
        $('#parent_id').val(commentId);
        $('textarea[name="comment"]').focus();
    });
});

$('.reply-link').on('click', function (e) {
    e.preventDefault();

    const commentId = $(this).data('id');
    $('#parent_id').val(commentId);

    // Pindahkan form ke bawah komentar yang ingin dibalas
    const target = $('#reply-target-' + commentId);
    $('#commentFormContainer').insertAfter(target);

    $('textarea[name="comment"]').focus();
});

// Tampilkan tombol batal
$('.reply-link').on('click', function () {
    $('#cancelReply').removeClass('d-none');
});

// Reset form ke posisi awal
$('#cancelReply').on('click', function () {
    $('#parent_id').val('');
    $('#commentFormContainer').appendTo('#defaultCommentPosition');
    $(this).addClass('d-none');
});

$(document).on('click', '.delete-comment', function (e) {
    e.preventDefault();
    const commentId = $(this).data('id');

    if (!confirm('Yakin ingin menghapus komentar ini?')) return;

    $.post("<?= base_url('material/comment/delete') ?>/" + commentId, {
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    })
    .done(function (response) {
        // Sembunyikan/hapus dari DOM
        $('#comment-' + commentId).fadeOut(300, function () {
            $(this).remove();
        });
    })
    .fail(function (xhr) {
        alert('Gagal menghapus komentar.');
    });
});


</script>
<?= $this->endSection() ?>