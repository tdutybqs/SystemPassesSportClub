<?php

require_once __DIR__ . "/Modules/Functions/appRun.php";
require_once __DIR__ . "/Modules/Functions/generalFunctions.php";
require_once __DIR__."/Modules/Classes/AppConfig.php";

$returnApp = app(
    include __DIR__ . "/Modules/Handlers/request.handler.php",
    $_SERVER['REQUEST_URI'],
    $_GET, 'loggerInFile',
new AppConfig());
try {
    render($returnApp['httpCode'], $returnApp['result']);
} catch (JsonException $e) {
    loggerInFile($e);
}
