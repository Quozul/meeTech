<?php

var_dump($_POST);

// delete empty values
foreach ($_POST as $key => $value)
    if (empty($value))
        unset($_POST[$key]);
