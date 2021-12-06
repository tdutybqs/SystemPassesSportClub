<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";
require_once __DIR__ . "/../Classes/Pass.php";
require_once __DIR__ . "/../Classes/Customer.php";

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
    $customers = loadData(__DIR__ . "/../../Jsons/customers.json");

    $logger('Переход на /pass выполнен');
    $customersIdToInfo = [];
    foreach ($customers as $currentCustomer) {
        $customersIdToInfo[$currentCustomer['customer_id']] = Customer::createFromArray($currentCustomer);
    }

    $findPasses = [];
    $paramsValidation = [
        'pass_id' => 'incorrect pass_id pass',
        'duration' => 'incorrect duration pass',
        'discount' => 'incorrect discount pass',
    ];
    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($passes as $pass) {
            if ($customersIdToInfo[$pass['customer_id']] === null) {
                continue;
            }
            $benefitPassObjToArray = [
                "customer_id" => $customersIdToInfo[$pass['customer_id']]->getId(),
                "full_name" => $customersIdToInfo[$pass['customer_id']]->getFullName(),
                "sex" => $customersIdToInfo[$pass['customer_id']]->getSex(),
                "birthdate" => $customersIdToInfo[$pass['customer_id']]->getBirthdate(),
                "phone" => $customersIdToInfo[$pass['customer_id']]->getPhone(),
                "passport" => $customersIdToInfo[$pass['customer_id']]->getPassport()
            ];
            $searchCriteriaMet = checkCriteria($request, array_merge($pass, $benefitPassObjToArray));

            if ($searchCriteriaMet) {
                $pass['customer'] = $customersIdToInfo[$pass['customer_id']];
                $findPasses[] = Pass::createFromArray($pass);
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

