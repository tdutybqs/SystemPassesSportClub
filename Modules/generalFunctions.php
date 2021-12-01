<?php

/**
 * Загрузка данных из ресурса
 *
 * @param string $sourcePath - путь до файла
 * @return array
 */
function loadData(string $sourcePath): array
{
    $pathToFile = $sourcePath;
    $content = file_get_contents($pathToFile);
    return json_decode($content, true);
}

/**
 * Валидация входяшщих параметров на соответствие заданному типу
 *
 * @param array $validateParameters - валидируемые параметры. ключ имя параметраб а значение - это текст сообщения об ошибке
 * @param array $params - все множество параметров
 *
 * @return array
 */
function paramTypeValidation(array $validateParameters, array $params):?array
{
//    if (array_key_exists($paramName, $params) && false === is_string($_GET[$paramName])) {
//        errorHandling('fail', $errMsg, 500);
//    }
    $result = null;
    foreach ($validateParameters as $paramName => $errMsg){
        if(array_key_exists($paramName, $params) && false === is_string($params[$paramName])){
            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg,
                ],

            ];
            break;
        }
    }
    return $result;
}

/**
 * Логирует текстовое сообщение
 *
 * @param string $errMsg - логируемое сообщение
 */
function logger(string $errMsg): void
{
    file_put_contents(__DIR__."/../Logs/app.log", $errMsg . "\n", FILE_APPEND);
}

/**
 * @param int $httpCode - http код
 * @param array $data - данные, которые мы хотим отобразить
 */
function render(int $httpCode, array $data): void
{
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data);
    exit();
}

/**
 * @param string $status - статус ответа
 * @param string $message - сообщение о сбое
 * @param int $httpCode - http код
 */
function errorHandling(string $status, string $message, int $httpCode): void
{
    $result = [
        'status' => $status,
        'message' => $message
    ];
    logger($message);
    render($httpCode, $result);
}