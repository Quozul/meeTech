<nav aria-label="..." class="mt-4">
    <?php
    $sth = $pdo->prepare('SELECT COUNT(*) FROM message WHERE category = ?');
    $sth->execute([$page_name]);
    $content_count = $sth->fetch()[0];
    ?>
    <ul class="pagination justify-content-center">
        <?php
        $page_count = $content_count / $page_limit;
        ?>
        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo '?page=' . (intval($page) - 1); ?>" tabindex="-1">Précédent</a>
        </li>
        <?php
        for ($i = 1; $i < $page_count + 1 ; $i++) {
            if ($i == $page) {
        ?>
                <li class="page-item active">
                    <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?> <span class="sr-only">(current)</span></a>
                </li>
            <?php } else { ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo '?page=' . $i; ?>"><?php echo $i; ?></a>
                </li>
        <?php }
        } ?>
        <li class="page-item <?php if ($page >= $page_count) echo 'disabled'; ?>">
            <a class="page-link" href="<?php echo '?page=' . (intval($page) + 1); ?>">Suivant</a>
        </li>
    </ul>
</nav>