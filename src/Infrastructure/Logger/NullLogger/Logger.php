<?php

namespace EfTech\SportClub\Infrastructure\Logger\NullLogger;

require_once __DIR__ . '/../LoggerInterface.php';

use EfTech\SportClub\Infrastructure\Logger\LoggerInterface;

/**
 * Логирует в "никуда" в null
 */
class Logger implements LoggerInterface
{

    /**
     * Запись сообщения в лог
     * @param string $msg - логируемое сообщение
     * @return void
     */
    public function log(string $msg): void
    {
        // TODO: Implement log() method.
    }
}