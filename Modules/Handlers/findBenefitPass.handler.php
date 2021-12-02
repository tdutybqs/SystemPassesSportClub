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
return static function (array $request, callable $logger): array
{
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
        $benefitPassIdToInfo = [];
        foreach ($benefitPasses as $benefitPass) {
            $benefitPassIdToInfo[$benefitPass['pass_id']] = $benefitPass;
        }

        $customerIdToInfo = [];
        foreach ($customers as $customerInfo) {
            $customerIdToInfo[$customerInfo['customer_id']] = $customerInfo;
        }

        foreach ($customers as $customer) {
            if (array_key_exists("sex", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['sex'] ===
                    $customerIdToInfo[$customer["customer_id"]]["sex"];
            } else {
                $benefitPassPurchaseReportsMeetSearchCriteria = true;
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("birthdate", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['birthdate'] ===
                    $customerIdToInfo[$customer["customer_id"]]["birthdate"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("phone", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request["phone"] ===
                    $customerIdToInfo[$customer["customer_id"]]["phone"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("passport", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['passport'] ===
                    $customerIdToInfo[$customer["customer_id"]]["passport"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("customer_id", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = (int)$request['customer_id'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["customer_id"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("full_name", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['full_name'] ===
                    $customer["full_name"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("type_benefit", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['type_benefit'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["type_benefit"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("number_document", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['number_document'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["number_document"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("end", $request)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $request['end'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["end"];
            }

            if ($benefitPassPurchaseReportsMeetSearchCriteria && $benefitPassIdToInfo[$customer["customer_id"]] !== null) {
                $benefitPass = $benefitPassIdToInfo[$customer["customer_id"]];
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