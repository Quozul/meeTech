<?php
include('../../config.php');

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    exit();
}

$cols = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/specifications.json'), true);

// TODO: Check if user have permission to add validated components according to badge
$validated = 0;

// get type then removes it from post array
$type = $_POST['type'];
unset($_POST['type']);

$sources = htmlspecialchars($_POST['sources']);
unset($_POST['sources']);

foreach ($_POST as $key => $value) {
    // removes special chars to prevent xss
    $_POST[$key] = htmlspecialchars($value);

    // unset all empty values
    if ($value === "")
        unset($_POST[$key]);
}

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
foreach ($values as $key => $value) {
    $i = 0;
    if (isset($specs[$value]))
        if (is_numeric($specs[$value]))
            $i = $specs[$value];
        else if ($specs[$value] == 'yes')
            $i = 1;

    $formula = str_replace('[' . $value . ']', $i, $formula);
}

var_dump($values);
echo $formula . '<br>';
$score = round(eval('return ' . $formula . ';'));
echo $score;

// send component to pdo
try {
    $sth = $pdo->prepare('INSERT INTO component (added_by, sources, validated, type, specifications, score) VALUES (?, ?, ?, ?, ?, ?);');
    $sth->execute([$_SESSION['userid'], $sources, $validated, $type, $specs, $score]);
} catch (Exception $e) {
    echo $e;
}
