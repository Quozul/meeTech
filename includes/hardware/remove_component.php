<?php
include('../../config.php');

var_dump($_POST);
$sth = $pdo->prepare('DELETE FROM component WHERE id = ?;');
$sth->execute([$_POST['id']]);
echo 'Done!';
?>

<noscript>JavaScript needs to be enabled to go back automatically</noscript>

<a href="javascript: history.go(-1)">Back</a>
<script>
    history.back();
</script>