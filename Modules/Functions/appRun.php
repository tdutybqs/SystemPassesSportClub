<?php

/**
 * Реализация веб приложения
 *
 * @param array handlers - массив, сопоставляющий urlPath с функцией обработчиком
 * @param string $requestUri - uri запроса
 * @param array request - параметры, которые передает пользователь
 * @param callable logger - функция, инкапсулирующая логику логгера
 * @return array
 */
function app(array $handlers, string $requestUri, array $request, callable $logger): array
{
    $urlPath = parse_url($requestUri, PHP_URL_PATH);
    $logger("Переход на " . urldecode($requestUri));

    if (array_key_exists($urlPath, $handlers)) {
        $result = $handlers[$urlPath]($request, $logger);
    } else {
        $result = [
            'httpCode' => 404,
            'result' => [
                'status' => 'fail',
                'message' => 'unsupported request'
            ]
        ];
    }
    return $result;
}





