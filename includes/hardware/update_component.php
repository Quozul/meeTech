<?php
include('../../config.php');
$cols = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/specifications.json'), true);

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

// get formula for score
$formula = $cols[$type]['score-formula'];
echo $formula . '<br>';

// https://stackoverflow.com/questions/27078259/get-string-between-find-all-occurrences-php
function getContents($str, $startDelimiter, $endDelimiter)
{
    $contents = array();
    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;
    while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
        $contentStart += $startDelimiterLength;
        $contentEnd = strpos($str, $endDelimiter, $contentStart);
        if (false === $contentEnd) {
            break;
        }
        $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
        $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $contents;
}

// get all variable names
$values = getContents($formula, '[', ']');

// calculate score
$score = 0;
foreach ($values as $key => $value)
    $formula = str_replace('[' . $value . ']', isset($_POST[$value]) && is_numeric($_POST[$value]) ? $_POST[$value] : 1, $formula);

var_dump($values);
echo $formula . '<br>';
$score = round(eval('return ' . $formula . ';'));
echo $score;

// send component to pdo
try {
    $sth = $pdo->prepare('UPDATE component SET validated = ?, type = ?, specifications = ?, score = ? WHERE id = ?;');
    $sth->execute([$validated, $type, $specs, $score, $_POST['id']]);
} catch (Exception $e) {
    echo $e;
}
?>

<noscript>JavaScript needs to be enabled to go back automatically</noscript>

<a href="javascript: history.go(-1)">Back</a>
<script>
    history.back();
</script>