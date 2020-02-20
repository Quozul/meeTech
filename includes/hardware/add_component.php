<?php
include('../../config.php');
// TODO: Check if user have permission to add validated components according to badge
$validated = 0;

// get type then removes it from post array
$type = $_POST['type'];
unset($_POST['type']);

// unset all empty values
foreach ($_POST as $key => $value)
    if ($value === "")
        unset($_POST[$key]);

// converts the post array to json
$specs = json_encode($_POST);

try {
    $sth = $pdo->prepare('INSERT INTO component (validated, type, specifications) VALUES (?, ?, ?);');
    $sth->execute([$validated, $type, $specs]);
} catch (Exception $e) {
    echo $e;
}
?>

<noscript>JavaScript needs to be enabled to go back automatically</noscript>

<a href="javascript: history.go(-1)">Back</a>
<script>
    history.back();
</script>