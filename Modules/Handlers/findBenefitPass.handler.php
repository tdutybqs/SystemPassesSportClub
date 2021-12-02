<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";

/**
 * Функция обработки поиска льгот
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array {
    $logger('Переход на: /benefit_pass выполнен.');

    $benefitPasses = loadData(__DIR__ . "/../../Jsons/benefit_pass.json");
    $customers = loadData(__DIR__ . "/../../Jsons/customers.json");

    $findCustomers = [];
    $paramsValidation = [
        'sex' => 'incorrect param "sex"',
        'birthdate' => 'incorrect param "birthdate"',
        'phone' => 'incorrect param "phone"',
        'passport' => 'incorrect param "passport"',
        'customer_id' => 'incorrect param "customer_id"',
        'full_name' => 'incorrect param "full_name"',
        'type_benefit' => 'incorrect param "type_benefit"',
        'number_document' => 'incorrect param "number_document"',
        'end' => 'incorrect param "end"',
    ];

    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        $customerIdToBenefitPass = [];
        foreach ($benefitPasses as $benefitPass) {
            $customerIdToBenefitPass[$benefitPass['customer_id']] = $benefitPass;
        }

        foreach ($customers as $customer) {
            $benefitPassPurchaseReportsMeetSearchCriteria = checkCriteria($request, $customer);
            if ($benefitPassPurchaseReportsMeetSearchCriteria === null && array_key_exists($customer['customer_id'],
                    $customerIdToBenefitPass)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = checkCriteria($request,
                    $customerIdToBenefitPass[$customer['customer_id']]);
            }

            if ($benefitPassPurchaseReportsMeetSearchCriteria && $customerIdToBenefitPass[$customer["customer_id"]] !== null) {
                $benefitPass = $customerIdToBenefitPass[$customer["customer_id"]];
                $customer["benefit"] = $benefitPass;
                unset($customer["benefit"]["pass_id"], $customer["benefit"]["discount"], $customer["benefit"]["customer_id"], $customer["benefit"]["duration"]);
                $findCustomers[] = $customer;
            }
        }
        $logger('Найдено ' . count($findCustomers) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findCustomers
        ];
    }
    return $result;
};