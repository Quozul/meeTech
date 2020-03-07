<?php
include('../../config.php');

// verify if user is connected
if (!isset($_SESSION['userid'])) {
    http_response_code(401);
    exit();
}

$empty_values = [];
if (empty($_POST['type']))
    array_push($empty_values, 'type');
if (empty($_POST['brand']))
    array_push($empty_values, 'brand');
if (empty($_POST['name']))
    array_push($empty_values, 'name');

if (count($empty_values) != 0) {
    echo json_encode($empty_values);
    http_response_code(417);
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

$brand = htmlspecialchars($_POST['brand']);
unset($_POST['brand']);

$name = htmlspecialchars($_POST['name']);
unset($_POST['name']);

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
    if (isset($_POST[$value]))
        if (is_numeric(strval($_POST[$value])))
            $i = $_POST[$value];
        else if ($_POST[$value] == 'yes')
            $i = 1;

    $formula = str_replace('[' . $value . ']', $i, $formula);
}

$score = round(eval('return ' . $formula . ';'));

// send component to pdo
try {
    $sth = $pdo->prepare('INSERT INTO component (added_by, brand, name, sources, validated, type, specifications, score) VALUES (?, ?, ?, ?, ?, ?, ?, ?);');
    $sth->execute([$_SESSION['userid'], $brand, $name, $sources, $validated, $type, $specs, $score]);
} catch (Exception $e) {
    echo $e;
}
