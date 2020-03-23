<?php
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
        if (false === $contentEnd)
            break;
        $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
        $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $contents;
}

// Score calculation
function score($pdo, $type, $comp_id)
{
    // Get formula
    $req = $pdo->prepare('SELECT score_formula FROM component_type WHERE id_t = ?');
    $req->execute([$type]);
    $formula = $req->fetch()[0];

    // Get all variable names
    $specs = getContents($formula, '[', ']');

    // Calculate score
    foreach ($specs as $key => $spec) {
        // Get specification value for component
        $req = $pdo->prepare('SELECT value FROM specification WHERE specification = ? AND component = ?');
        $req->execute([$spec, $comp_id]);
        $value = $req->fetch();

        $formula = str_replace('[' . $spec . ']', $value ? $value[0] : 0, $formula);
    }

    $score = round(eval('return ' . $formula . ';'));

    // Update score
    $req = $pdo->prepare('UPDATE component SET score = ? WHERE id_c = ?');
    $req->execute([$score, $comp_id]);
}
