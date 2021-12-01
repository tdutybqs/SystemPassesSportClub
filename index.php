<?php

include "Modules/generalFunctions.php";
include "Modules/appRun.php";

$returnApp = app(
    $_SERVER['REQUEST_URI'],
    $_GET,
    'loggerInFile'
);
render($returnApp['httpCode'], $returnApp['result']);
