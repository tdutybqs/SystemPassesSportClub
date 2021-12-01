<?php


/**
 * Реализация веб приложения
 *
 * @param string $requestUri - uri запроса
 * @param array $request - параметры, которые передает пользователь
 * @param callable $logger - функция, инкапсулирующая логику логгера
 * @return array
 */
function app(string $requestUri, array $request, callable $logger): array
{
    $urlPath = parse_url($requestUri, PHP_URL_PATH);
    $logger("Переход на " . urldecode($requestUri));

    if ('/benefit_pass' === $urlPath) {
        $result = findBenefitPass($request, $logger);
    } elseif ('/programmes' === $urlPath) {
        $result = findProgrammes($request, $logger);
    } elseif ('/pass' === $urlPath) {
        $result = findPass($request, $logger);
    } elseif ('/purchased_items' === $urlPath) {
        $result = findPurchasedItems($request, $logger);
    } else {
        $result = [
            'httpCode' => 404,
            'result' => [
                'status' => 'fail',
                'message' => 'unsupported request'
            ]
        ];
    }
    return $result;
}


/**
 * Функция обработки поиска льгот
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 */
function findBenefitPass(array $request, callable $logger): array
{
    $logger('Переход на: /benefit_pass выполнен.');

    $benefitPasses = loadData(__DIR__ . "/../Jsons/benefit_pass.json");
    $customers = loadData(__DIR__ . "/../Jsons/customers.json");

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
                unset($customer["benefit"]["pass_id"]);
                unset($customer["benefit"]["discount"]);
                unset($customer["benefit"]["customer_id"]);
                unset($customer["benefit"]["duration"]);
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
}

/**
 * Функция обработки поиска программ
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 */
function findProgrammes(array $request, callable $logger): array
{
    $logger('Переход на /programmes выполнен ');

    $programmes = loadData(__DIR__ . "/../Jsons/programmes.json");

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
}

/**
 * Функция, показывающая абонементы пользователей
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 */
function findPass(array $request, callable $logger): array
{
    $passes = loadData(__DIR__ . "/../Jsons/pass.json");

    $logger('Переход на /pass выполнен');

    $findPasses = [];
    $paramsValidation = [
        'pass_id' => 'incorrect pass_id pass',
        'duration' => 'incorrect duration pass',
        'discount' => 'incorrect discount pass',
    ];
    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        foreach ($passes as $pass) {
            if (array_key_exists("pass_id", $request)) {
                $searchCriteriaMet = $pass['pass_id'] === (int)$request['pass_id'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("duration", $request)) {
                $searchCriteriaMet = $pass['duration'] === $request['duration'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_id", $request)) {
                $searchCriteriaMet = $pass['customer_id'] === (int)$request['customer_id'];
            }
            if ($searchCriteriaMet && array_key_exists("discount", $request)) {
                $searchCriteriaMet = $pass['discount'] === $request['discount'];
            }
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
}

/**
 * Поиск покупаемых программ пользователем
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 */
function findPurchasedItems(array $request, callable $logger): array
{
    $customerResult = [];

    // Подключаем нужные JSON's
    $passes = loadData(__DIR__ . "/../Jsons/pass.json");
    $customers = loadData(__DIR__ . "/../Jsons/customers.json");
    $purchasedItems = loadData(__DIR__ . "/../Jsons/purchased_item.json");

    $logger('Переход на /purchased_items выполнен');

    $paramsValidation = [
        'customer_id' => 'incorrect customer_id',
        'customer_full_name' => 'incorrect customer_full_name',
        'customer_sex' => 'incorrect customer_sex',
        'customer_birthdate' => 'incorrect customer_birthdate',
        'customer_phone' => 'incorrect customer_phone',
        'customer_passport' => 'incorrect customer_passport',
        'price' => 'incorrect price',
        'purchased_item_id' => 'incorrect purchased_item_id',
        'pass_id' => 'incorrect pass_id',
        'id_programme' => 'incorrect id_programme',
    ];
    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        $customerIdToInfo = [];
        foreach ($customers as $customerInfo) {
            $customerIdToInfo[$customerInfo['customer_id']] = $customerInfo;
        }
        $customerIdToPurchasedItem = [];

        $passesIdToInfo = [];
        foreach ($passes as $passInfo) {
            $passesIdToInfo[$passInfo['pass_id']] = $passInfo;
        }

        foreach ($purchasedItems as $purchasedItem) {
            $customerId = $passesIdToInfo[$purchasedItem['pass_id']]['customer_id'];

            if (array_key_exists("customer_id", $request)) {
                $searchCriteriaMet = $customerId === (int)$request['customer_id'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("customer_full_name", $request)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['full_name'] === $request['customer_full_name'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_sex", $request)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['sex'] === $request['customer_sex'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_birthdate", $request)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['birthdate'] === $request['customer_birthdate'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_phone", $request)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['phone'] === $request['customer_phone'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_passport", $request)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['passport'] === $request['customer_passport'];
            }


            if ($searchCriteriaMet && array_key_exists('price', $request)) {
                $searchCriteriaMet = $purchasedItem['price'] === (int)$request['price'];
            }
            if ($searchCriteriaMet && array_key_exists('purchased_item_id', $request)) {
                $searchCriteriaMet = $purchasedItem['purchased_item_id'] === (int)$request['purchased_item_id'];
            }
            if ($searchCriteriaMet && array_key_exists('pass_id', $request)) {
                $searchCriteriaMet = $purchasedItem['pass_id'] === (int)$request['pass_id'];
            }
            if ($searchCriteriaMet && array_key_exists('id_programme', $request)) {
                $searchCriteriaMet = $purchasedItem['id_programme'] === (int)$request['id_programme'];
            }
            if ($searchCriteriaMet) {
                if (!array_key_exists($customerId, $customerResult)) {
                    $customerResult[$customerId] = true;
                    $result[] = $customerIdToInfo[$customerId];
                }
                if (!array_key_exists($customerId, $customerIdToPurchasedItem)) {
                    $customerIdToPurchasedItem[$customerId] = [];
                }
                $customerIdToPurchasedItem[$customerId][] = $purchasedItem;
            }
        }
        foreach ($result as &$customerInfo) {
            $customerInfo['purchased_items'] = $customerIdToPurchasedItem[$customerInfo['customer_id']];
        }
        $logger('Найдено ' . count($result) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $result
        ];
    }
    return $result;
}