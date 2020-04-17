<?php

header('Content-type: application/xml');

include($_SERVER['DOCUMENT_ROOT'] . '/config.php');

$req = $pdo->prepare('SHOW TABLES');
$req->execute();
$tables = $req->fetchAll();

$xml = "<meetech> \n";

foreach ($tables as $key => $table) {
    $table_name = $table[0];

    $req = $pdo->prepare('SELECT * FROM ' . $table_name);
    $req->execute();
    $res = $req->fetchAll();

    $xml = $xml . "    <$table_name>" . ($res ? " \n" : "");

    foreach ($res as $key => $row) {

        $xml = $xml . "        <$key";

        foreach ($row as $index => $col) {
            if (!is_numeric($index)) {
                $xml = $xml . " $index=\"$col\"";
            }
        }

        $xml = $xml . "/> \n";
    }

    $xml = $xml . ($res ? "    " : "") . "</$table_name> \n";
}

$xml = $xml . "</meetech>";

echo $xml;
