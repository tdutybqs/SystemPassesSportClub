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
            if (array_key_exists("sex", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['sex'] ===
                    $customerIdToInfo[$customer["customer_id"]]["sex"];
            } else {
                $benefitPassPurchaseReportsMeetSearchCriteria = true;
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("birthdate", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['birthdate'] ===
                    $customerIdToInfo[$customer["customer_id"]]["birthdate"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("phone", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET["phone"] ===
                    $customerIdToInfo[$customer["customer_id"]]["phone"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("passport", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['passport'] ===
                    $customerIdToInfo[$customer["customer_id"]]["passport"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("customer_id", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = (int)$_GET['customer_id'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["customer_id"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("full_name", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['full_name'] ===
                    $customer["full_name"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("type_benefit", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['type_benefit'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["type_benefit"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("number_document", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['number_document'] ===
                    $benefitPassIdToInfo[$customer["customer_id"]]["number_document"];
            }
            if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("end", $_GET)) {
                $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['end'] ===
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

    if (null === ($result = paramTypeValidation($paramsValidation, $_GET))) {
        foreach ($programmes as $programme) {
            if (array_key_exists("id_programme", $_GET)) {
                $searchCriteriaMet = $programme['id_programme'] === (int)$_GET['id_programme'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("name", $_GET)) {
                $searchCriteriaMet = $programme['name'] === $_GET['name'];
            }
            if ($searchCriteriaMet && array_key_exists("duration", $_GET)) {
                $searchCriteriaMet = (string)$programme['duration'] === $_GET['duration'];
            }
            if ($searchCriteriaMet && array_key_exists("discount", $_GET)) {
                $searchCriteriaMet = $programme['discount'] === $_GET['discount'];
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
    if (null === ($result = paramTypeValidation($paramsValidation, $_GET))) {
        foreach ($passes as $pass) {
            if (array_key_exists("pass_id", $_GET)) {
                $searchCriteriaMet = $pass['pass_id'] === (int)$_GET['pass_id'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("duration", $_GET)) {
                $searchCriteriaMet = $pass['duration'] === $_GET['duration'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_id", $_GET)) {
                $searchCriteriaMet = $pass['customer_id'] === (int)$_GET['customer_id'];
            }
            if ($searchCriteriaMet && array_key_exists("discount", $_GET)) {
                $searchCriteriaMet = $pass['discount'] === $_GET['discount'];
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
    if (null === ($result = paramTypeValidation($paramsValidation, $_GET))) {
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

            if (array_key_exists("customer_id", $_GET)) {
                $searchCriteriaMet = $customerId === (int)$_GET['customer_id'];
            } else {
                $searchCriteriaMet = true;
            }
            if ($searchCriteriaMet && array_key_exists("customer_full_name", $_GET)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['full_name'] === $_GET['customer_full_name'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_sex", $_GET)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['sex'] === $_GET['customer_sex'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_birthdate", $_GET)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['birthdate'] === $_GET['customer_birthdate'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_phone", $_GET)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['phone'] === $_GET['customer_phone'];
            }
            if ($searchCriteriaMet && array_key_exists("customer_passport", $_GET)) {
                $searchCriteriaMet = $customerIdToInfo[$customerId]['passport'] === $_GET['customer_passport'];
            }


            if ($searchCriteriaMet && array_key_exists('price', $_GET)) {
                $searchCriteriaMet = $purchasedItem['price'] === (int)$_GET['price'];
            }
            if ($searchCriteriaMet && array_key_exists('purchased_item_id', $_GET)) {
                $searchCriteriaMet = $purchasedItem['purchased_item_id'] === (int)$_GET['purchased_item_id'];
            }
            if ($searchCriteriaMet && array_key_exists('pass_id', $_GET)) {
                $searchCriteriaMet = $purchasedItem['pass_id'] === (int)$_GET['pass_id'];
            }
            if ($searchCriteriaMet && array_key_exists('id_programme', $_GET)) {
                $searchCriteriaMet = $purchasedItem['id_programme'] === (int)$_GET['id_programme'];
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