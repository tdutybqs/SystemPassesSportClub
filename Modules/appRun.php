<?php


/**
 * Реализация веб приложения
 *
 * @return array
 */
function app(): array
{
    $result = [];
    $pathInfo = array_key_exists("PATH_INFO", $_SERVER) && $_SERVER["PATH_INFO"] ? $_SERVER["PATH_INFO"] : '';

    if ('/benefit_pass' === $pathInfo) {
        $result = findBenefitPass();
    } elseif ('/programmes' === $pathInfo) {
        $result = findProgrammes();
    } elseif ('/pass' === $pathInfo) {
        $result = findPass();
    } elseif ('/purchased_items' === $pathInfo) {
        $result = findPurchasedItems();
    } else {
        errorHandling('fail', "unsupported request", 404);
    }
    return $result;
}


/**
 * Функция обработки поиска льгот
 *
 * @return array
 */
function findBenefitPass(): array
{
    $httpCode = 200;
    $result = [];
    logger('Переход на: /benefit_pass выполнен.');

    $benefitPasses = loadData(__DIR__ . "/../Jsons/benefit_pass.json");
    $customers = loadData(__DIR__ . "/../Jsons/customers.json");

    // Начинаем валидацию критериев поиска
    paramTypeValidation('sex', $_GET, 'incorrect param "sex"');
    paramTypeValidation('birthdate', $_GET, 'incorrect param "birthdate"');
    paramTypeValidation('phone', $_GET, 'incorrect param "phone"');
    paramTypeValidation('passport', $_GET, 'incorrect param "passport"');
    paramTypeValidation('customer_id', $_GET, 'incorrect param "customer_id"');
    paramTypeValidation('full_name', $_GET, 'incorrect param "full_name"');
    paramTypeValidation('type_benefit', $_GET, 'incorrect param "type_benefit"');
    paramTypeValidation('number_document', $_GET, 'incorrect param "number_document"');
    paramTypeValidation('end', $_GET, 'incorrect param "end"');
    // Конец валидации

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
            $result[] = $customer;
        }
    }
    logger('Найдено ' . count($result) . ' объектов.');
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}

/**
 * Функция обработки поиска программ
 *
 * @return array
 */
function findProgrammes(): array
{
    $httpCode = 200;
    $result = [];
    logger('Переход на /programmes выполнен ');
    $programmes = loadData(__DIR__ . "/../Jsons/programmes.json");

    //Начинаем валидацию критериев поиска 3 сценария
    paramTypeValidation('id_programme', $_GET, 'incorrect id_programme');
    paramTypeValidation('name', $_GET, 'incorrect name programme');
    paramTypeValidation('duration', $_GET, 'incorrect duration programme');
    paramTypeValidation('discount', $_GET, 'incorrect discount programme');
    // Конец валидации критериев поиска 3 сценария

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
            $result[] = $programme;
        }
    }
    logger('Найдено ' . count($result) . ' объектов.');
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}


function findPass():array{
    $httpCode = 200;
    $result = [];
    $passes = loadData(__DIR__ . "/../Jsons/pass.json");
    logger('Переход на /pass выполнен');

    // Начинаем валидацию параметров поиска 4 сценария
    paramTypeValidation('pass_id', $_GET, 'incorrect pass_id pass');
    paramTypeValidation('duration', $_GET, 'incorrect duration pass');
    paramTypeValidation('discount', $_GET, 'incorrect discount pass');
    // Конец валидации параметров поиска 4 сценария

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
        if ($searchCriteriaMet && array_key_exists("discount", $_GET)){
            $searchCriteriaMet = $pass['discount'] === $_GET['discount'];
        }
        if ($searchCriteriaMet) {
            $result[] = $pass;
        }
    }
    logger('Найдено ' . count($result) . ' объектов.');
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}


function findPurchasedItems():array{
    $httpCode = 200;
    $result = [];
    $customerResult = [];

    // Подключаем нужные JSON's
    $passes = loadData(__DIR__ . "/../Jsons/pass.json");
    $customers = loadData(__DIR__ . "/../Jsons/customers.json");
    $purchasedItems = loadData(__DIR__ . "/../Jsons/purchased_item.json");

    logger('Переход на /purchased_items выполнен');

    // Начало валидации параметров по клиенту
    paramTypeValidation('customer_id', $_GET, 'incorrect customer_id');
    paramTypeValidation('customer_full_name', $_GET, 'incorrect customer_full_name');
    paramTypeValidation('customer_sex', $_GET, 'incorrect customer_sex');
    paramTypeValidation('customer_birthdate', $_GET, 'incorrect customer_birthdate');
    paramTypeValidation('customer_phone', $_GET, 'incorrect customer_phone');
    paramTypeValidation('customer_passport', $_GET, 'incorrect customer_passport');
    // Конец валидации параметров по клиенту

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
    logger('Найдено ' . count($result) . ' объектов.');
    return [
        'httpCode' => $httpCode,
        'result' => $result
    ];
}