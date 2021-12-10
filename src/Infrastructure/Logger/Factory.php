<?php

require_once __DIR__ . '/LoggerInterface.php';
require_once __DIR__ . '/../AppConfig.php';

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
            $logger = new Logger($appConfig->getPathToLogFile());
        } elseif ('nullLogger' === $loggerType) {
            require_once __DIR__ . '/NullLogger/Logger.php';
            $logger = new Logger();
        } elseif ('echoLogger' === $loggerType) {
            require_once __DIR__ . '/EchoLogger/Logger.php';
            $logger = new Logger();
        } else {
            throw new Exception('Незивестный тип логера');
        }
        return $logger;
    }
}