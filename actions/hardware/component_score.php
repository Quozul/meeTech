<?php
function getContents($str)
{
    $content = [];
    preg_match_all("/\[(.*?)\]/", $str, $content);
    return $content[1];
}

// Score calculation
function score($pdo, $type, $comp_id)
{
    // Get formula
    $req = $pdo->prepare('SELECT score_formula FROM component_type WHERE id_t = ?');
    $req->execute([$type]);
    $formula = $req->fetch()[0];

    // Get all variable names
    $specs = getContents($formula);

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
