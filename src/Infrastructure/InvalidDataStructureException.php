<?php

namespace EfTech\SportClub\Infrastructure;

use RuntimeException;

/**
 * Исключение выбрасывается в случае, если данные с которыми работает приложение имеют не валидную структуру
 */
class InvalidDataStructureException extends RuntimeException
{

}