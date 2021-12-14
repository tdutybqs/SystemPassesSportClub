<?php

namespace EfTech\SportClub\Infrastructure\Logger;

/**
 * Интерфейс логера
 */
interface LoggerInterface
{
    /**
     * Запись сообщения в лог
     * @param string $msg - логируемое сообщение
     * @return void
     */
    public function log(string $msg): void;
}