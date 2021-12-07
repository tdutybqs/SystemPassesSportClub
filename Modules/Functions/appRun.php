<?php

require_once __DIR__ . "/../../Modules/Classes/AppConfig.php";

/**
 * Реализация веб приложения
 * @param array $handlers
 * @param string $requestUri - uri запроса
 * @param array $request
 * @param callable logger - функция, инкапсулирующая логику логгера
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 */
function app(array $handlers, string $requestUri, array $request, callable $logger, AppConfig $appConfig): array
{
    try {
        $urlPath = parse_url($requestUri, PHP_URL_PATH);
        $logger("Переход на " . urldecode($requestUri));

        if (array_key_exists($urlPath, $handlers)) {
            $result = $handlers[$urlPath]($request, $logger, $appConfig);
        } else {
            $result = [
                'httpCode' => 404,
                'result' => [
                    'status' => 'fail',
                    'message' => 'unsupported request'
                ]
            ];
        }
    } catch (Throwable $e) {
        $result = [
            'httpCode' => 500,
            'result' => [
                'status' => 'fail',
                'message' => $e->getMessage()
            ],
        ];
    }
    return $result;
}





