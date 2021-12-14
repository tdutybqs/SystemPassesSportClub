<?php

namespace EfTech\SportClub\Infrastructure\Logger;

use Exception;
use EfTech\SportClub\Infrastructure\AppConfig;
use EfTech\SportClub\Infrastructure\Logger\NullLogger\Logger as NullLogger;
use EfTech\SportClub\Infrastructure\Logger\FileLogger\Logger as FileLogger;
use EfTech\SportClub\Infrastructure\Logger\EchoLogger\Logger as EchoLogger;

require_once __DIR__ . '/LoggerInterface.php';
require_once __DIR__ . '/../AppConfig.php';
require_once __DIR__ . '/../appRun.php';            //  один из них
require_once __DIR__ . '/../generalFunctions.php';  //     нужен

/**
 * Фабрика по созданию логеров
 */
class Factory
{
    /**
     * Реализация логики создания логеров
     * @param AppConfig $appConfig
     * @return LoggerInterface
     * @throws Exception - неизвестный тип логера
     */
    public static function create(AppConfig $appConfig): LoggerInterface
    {
        $loggerType = $appConfig->getLoggerType();
        if ('fileLogger' === $loggerType) {
            require_once __DIR__ . '/FileLogger/Logger.php';
            $logger = new FileLogger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $loggerType) {
            require_once __DIR__ . '/NullLogger/Logger.php';
            $logger = new NullLogger();
        } elseif ('echoLogger' === $loggerType) {
            require_once __DIR__ . '/EchoLogger/Logger.php';
            $logger = new EchoLogger();
        } else {
            throw new Exception('Незивестный тип логера');
        }
        return $logger;
    }
}