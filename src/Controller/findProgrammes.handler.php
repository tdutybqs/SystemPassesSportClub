<?php

namespace Controller;

use EfTech\Sportclub\Entity\Programme;
use Exception;
use EfTech\Sportclub\Infrastructure\AppConfig;
use EfTech\Sportclub\Infrastructure\Logger\LoggerInterface;
use function EfTech\Sportclub\Infrastructure\loadData;
use function EfTech\Sportclub\Infrastructure\paramTypeValidation;

include_once __DIR__ . "/../Infrastructure/generalFunctions.php";
require_once __DIR__ . "/../Entity/Programme.php";

include_once __DIR__."/../Infrastructure/Logger/LoggerInterface.php";
require_once __DIR__ . "/../Infrastructure/AppConfig.php";

/**
 * Функция обработки поиска программ
 * @param array request - параметры, которые передает пользователь
 * @param LoggerInterface $logger -  компонент, отвечающий за логирование
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 * @throws Exception
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
    $logger->log('Переход на /programmes выполнен ');

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
            $searchCriteriaMet = \EfTech\SportClub\Infrastructure\checkCriteria($request, $programme);
            if ($searchCriteriaMet) {
                $findProgrammes[] = Programme::createFromArray($programme);
            }
        }
        $logger->log('Найдено ' . count($findProgrammes) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findProgrammes
        ];
    }
    return $result;
};