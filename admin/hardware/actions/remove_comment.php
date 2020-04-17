<?php include($_SERVER['DOCUMENT_ROOT'] . '/config.php');
$req = $pdo->prepare('DELETE FROM component_comment where id_c = ?');
$req->execute([$_POST['id']]); ?>
<script>
    window.history.back();
</script>