<?php

namespace EfTech\SportClub\Infrastructure\Logger\FileLogger;

use EfTech\SportClub\Infrastructure\Logger\LoggerInterface;

require_once __DIR__ . "/../LoggerInterface.php";

/**
 * Логирование в файл
 */
class Logger implements LoggerInterface
{
    /**
     * Путь до файла куда логи пишутся
     * @var string
     */
    private string $pathToFile;

    /**
     * @param string $pathToFile - путь до файла
     */
    public function __construct(string $pathToFile)
    {
        $this->pathToFile = $pathToFile;
    }

    /**
     * Запись сообщения в лог
     * @param string $msg - логируемое сообщение
     * @return void
     */
    public function log(string $msg): void
    {
        file_put_contents($this->pathToFile, "$msg\n", FILE_APPEND);
    }

}