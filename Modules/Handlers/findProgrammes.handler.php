<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";
require_once __DIR__ . "/../Classes/Programme.php";


/**
 * Функция обработки поиска программ
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger, AppConfig $appConfig): array {
    $logger('Переход на /programmes выполнен ');

    $programmes = loadData($appConfig->getPathToProgrammes());

    $findProgrammes = [];
    $paramsValidation = [
        'id_programme' => 'incorrect id_programme',
        'name' => 'incorrect name programme',
        'duration' => 'incorrect duration programme',
        'discount' => 'incorrect discount programme',
    ];

    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($programmes as $programme) {
            $searchCriteriaMet = checkCriteria($request, $programme);
            if ($searchCriteriaMet) {
                $findProgrammes[] = Programme::createFromArray($programme);
            }
        }
        $logger('Найдено ' . count($findProgrammes) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findProgrammes
        ];
    }
    return $result;
};