<?php

use EfTech\SportClub\Infrastructure\AppConfig;
use function EfTech\SportClub\Infrastructure\app;
use function EfTech\SportClub\Infrastructure\render;

require_once __DIR__ . "/../src/Infrastructure/appRun.php";
require_once __DIR__ . "/../src/Infrastructure/generalFunctions.php";
require_once __DIR__ . "/../src/Infrastructure/AppConfig.php";
require_once __DIR__ . "/../src/Infrastructure/Logger/Factory.php";

$returnApp = app(
    include __DIR__ . "/../config/request.handler.php",
    $_SERVER['REQUEST_URI'],
    '\EfTech\SportClub\Infrastructure\Logger\Factory::create',
    static function () {
        return AppConfig::createFromArray(include __DIR__ . "/../config/dev/config.php");
    });
try {
    render($returnApp['httpCode'], $returnApp['result']);
} catch (JsonException $e) {
    \EfTech\SportClub\Infrastructure\loggerInFile($e);
}