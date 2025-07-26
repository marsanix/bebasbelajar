<?php

function buildCommentTree($comments, $parentId = null) {
    $branch = [];

    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parentId) {
            $children = buildCommentTree($comments, $comment['id']);
            if ($children) {
                $comment['replies'] = $children;
            }
            $branch[] = $comment;
        }
    }

    return $branch;
}

function renderComments($comments, $level = 0) {
    foreach ($comments as $c): ?>
        <div class="mt-3" style="margin-left: <?= $level * 30 ?>px">
            <div class="border p-2 rounded bg-light">
                <strong><?= esc($c['user_name']) ?></strong><br>
                <div><?= esc($c['comment']) ?></div>
                <small class="text-muted"><?= date('d M Y H:i', strtotime($c['created_at'])) ?></small>
                
                <?php if (session('isLoggedIn')): ?>
                    <a href="#" class="reply-link btn btn-sm btn-link" data-id="<?= $c['id'] ?>">Balas</a>
                    <?php if (session('user_id') == $c['user_id']): ?>
                        <a href="#" class="text-danger btn btn-sm btn-link delete-comment" data-id="<?= $c['id'] ?>">Hapus</a>
                    <?php endif; ?>

                    <div id="reply-target-<?= $c['id'] ?>"></div>
                <?php endif; ?>
            </div>

            <!-- Balasan -->
            <?php if (!empty($c['replies'])) {
                renderComments($c['replies'], $level + 1);
            } ?>
        </div>
    <?php endforeach;
} ?>
