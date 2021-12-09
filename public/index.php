<?php

// TODO
require_once __DIR__ . "/../src/Infrastructure/appRun.php";
require_once __DIR__ . "/../src/Infrastructure/generalFunctions.php";
require_once __DIR__ . "/../src/Infrastructure/AppConfig.php";

$returnApp = app(
    include __DIR__ . "/../config/request.handler.php",
    $_SERVER['REQUEST_URI'],
    'loggerInFile',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . "/../config/dev/config.php");
    });
try {
    render($returnApp['httpCode'], $returnApp['result']);
} catch (JsonException $e) {
    loggerInFile($e);
}
