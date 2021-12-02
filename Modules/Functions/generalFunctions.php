<?php

/**
 * Загрузка данных из ресурса
 *
 * @param string $sourcePath - путь до файла
 * @return array
 * @throws JsonException
 */
function loadData(string $sourcePath): array
{
    $pathToFile = $sourcePath;
    $content = file_get_contents($pathToFile);
    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
}

/**
 * Валидация входяшщих параметров на соответствие заданному типу
 *
 * @param array $validateParameters - валидируемые параметры, ключ имя параметра, а значение это текст сообщения об ошибке
 * @param array $params - все множество параметров
 * @return array - возвращает массив с сообщением об ошибке, иначе null
 */
function paramTypeValidation(array $validateParameters, array $params): ?array
{
    $result = null;
    foreach ($validateParameters as $paramName => $errMsg) {
        if (array_key_exists($paramName, $params) && false === is_string($params[$paramName])) {
            $result = [
                'httpCode' => 500,
                'result' => [
                    'status' => 'fail',
                    'message' => $errMsg,
                ]
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
function loggerInFile(string $errMsg): void
{
    file_put_contents(__DIR__ . "/../../Logs/app.log", $errMsg . "\n", FILE_APPEND);
}

/**
 * @param int $httpCode - http код
 * @param array $data - данные, которые мы хотим отобразить
 * @throws JsonException
 */
function render(int $httpCode, array $data): void
{
    header('Content-Type: application/json');
    http_response_code($httpCode);
    echo json_encode($data, JSON_THROW_ON_ERROR);
    exit();
}


///**
// * Функция, сокращающая портянки
// *
// * @param array $data - сущность, по которой производить поиск
// * @param array $request - $_GET
// * @return bool
// */
//function searchCriteriaMet(array $data, array $request): bool
//{
//    $startValue = true;
//    foreach ($request as $criteriaForSearch => $index) {
//        $startValue = (string)$data[$criteriaForSearch] === $index;
//        if ($startValue === false) {
//            return false;
//        }
//    }
//    return $startValue;
//}
