<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";

/**
 * Функция, показывающая абонементы пользователей
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array {
    $passes = loadData(__DIR__ . "/../../Jsons/pass.json");

    $logger('Переход на /pass выполнен');

    $findPasses = [];
    $paramsValidation = [
        'pass_id' => 'incorrect pass_id pass',
        'duration' => 'incorrect duration pass',
        'discount' => 'incorrect discount pass',
    ];
    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($passes as $pass) {
            $searchCriteriaMet = checkCriteria($request, $pass);
            if ($searchCriteriaMet) {
                $findPasses[] = $pass;
            }
        }
        $logger('Найдено ' . count($findPasses) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findPasses
        ];
    }
    return $result;
};

