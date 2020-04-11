<?php include('../../config.php') ?>
<?php
session_destroy();
exit;

header('location:index.php');
?>