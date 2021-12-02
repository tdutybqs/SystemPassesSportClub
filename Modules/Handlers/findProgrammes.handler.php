<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";

/**
 * Функция обработки поиска программ
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array
{
    $logger('Переход на /programmes выполнен ');

    $programmes = loadData(__DIR__ . "/../../Jsons/programmes.json");

    $findProgrammes = [];
    $paramsValidation = [
        'id_programme' => 'incorrect id_programme',
        'name' => 'incorrect name programme',
        'duration' => 'incorrect duration programme',
        'discount' => 'incorrect discount programme',
    ];

    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($programmes as $programme) {
            if (array_key_exists("id_programme", $request)) {
                $searchCriteriaMet = $programme['id_programme'] === (int)$request['id_programme'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("name", $request)) {
                $searchCriteriaMet = $programme['name'] === $request['name'];
            }
            if ($searchCriteriaMet && array_key_exists("duration", $request)) {
                $searchCriteriaMet = (string)$programme['duration'] === $request['duration'];
            }
            if ($searchCriteriaMet && array_key_exists("discount", $request)) {
                $searchCriteriaMet = $programme['discount'] === $request['discount'];
            }
            if ($searchCriteriaMet) {
                $findProgrammes[] = $programme;
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