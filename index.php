<?php

include "Modules/generalFunctions.php";
include "Modules/appRun.php";

$returnApp = app();
render($returnApp['httpCode'], $returnApp['result']);
