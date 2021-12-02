<?php

require_once __DIR__ . "/Modules/Functions/appRun.php";
require_once __DIR__ . "/Modules/Functions/generalFunctions.php";


//$data = [
//    'pass_id'=>3
//];
//$criteria = ['pass_id'=>1];
//searchCriteriaMet($data, $criteria, $_GET);

$returnApp = app(
    include __DIR__ . "/Modules/Handlers/request.handler.php",
    $_SERVER['REQUEST_URI'],
    $_GET, 'loggerInFile');
try {
    render($returnApp['httpCode'], $returnApp['result']);
} catch (JsonException $e) {
    loggerInFile($e);
}
