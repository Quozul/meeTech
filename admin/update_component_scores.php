<?php
include('../config.php');
$cols = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/includes/hardware/specifications.json'), true);
?>

Please wait until the page finishes loading, we're processing your request...<br><br>

<?php
$sth = $pdo->prepare('SELECT * FROM component');
$sth->execute();
$components = $sth->fetchAll();

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

foreach ($components as $key => $component) {
    $specs = json_decode($component['specifications'], true);
    $type = $component['type'];
    echo $component['id'] . ' ' . $type . ' ' . $specs['brand'] . ' ' . $specs['name'];

    // get formula for score
    $formula = $cols[$type]['score-formula'];

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

    $score = round(eval('return ' . $formula . ';'));

    if ($score != $component['score']) {

        // send component to pdo
        try {
            $sth = $pdo->prepare('UPDATE component SET score = ? WHERE id = ?;');
            $sth->execute([$score, $component['id']]);
        } catch (Exception $e) {
            echo $e . '<br>';
        }
    } else
        echo ' untouched';
    echo '<br>';
}
?>

<br>Done! All score have been updated!<br>

<noscript>JavaScript needs to be enabled to go back automatically</noscript>
<a href="javascript: history.go(-1)">Back</a>
<script>
    // history.back();
</script>