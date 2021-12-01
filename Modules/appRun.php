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
            $printThis = $programme['id_programme'] === (int)$_GET['id_programme'];
        } else {
            $printThis = true;
        }
        if ($printThis && array_key_exists("name", $_GET)) {
            $printThis = $programme['name'] === $_GET['name'];
        }
        if ($printThis && array_key_exists("duration", $_GET)) {
            $printThis = (string)$programme['duration'] === $_GET['duration'];
        }
        if ($printThis && array_key_exists("discount", $_GET)) {
            $printThis = $programme['discount'] === $_GET['discount'];
        }
        if ($printThis) {
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
            $printThis = $pass['pass_id'] === (int)$_GET['pass_id'];
        } else {
            $printThis = true;
        }
        if ($printThis && array_key_exists("duration", $_GET)) {
            $printThis = $pass['duration'] === $_GET['duration'];
        }
        if ($printThis && array_key_exists("customer_id", $_GET)) {
            $printThis = $pass['customer_id'] === (int)$_GET['customer_id'];
        }
        if ($printThis) {
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

    $paramValidations = [
        'customer_id' => 'incorrect customer id',
        'title' => 'incorrect customer_id',
    ];
    if (null === ($result = paramTypeValidation($paramValidations, $_GET))){
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
                $printThis = $customerId === (int)$_GET['customer_id'];
            } else {
                $printThis = true;
            }
            if ($printThis && array_key_exists("customer_full_name", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['full_name'] === $_GET['customer_full_name'];
            }
            if ($printThis && array_key_exists("customer_sex", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['sex'] === $_GET['customer_sex'];
            }
            if ($printThis && array_key_exists("customer_birthdate", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['birthdate'] === $_GET['customer_birthdate'];
            }
            if ($printThis && array_key_exists("customer_phone", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['phone'] === $_GET['customer_phone'];
            }
            if ($printThis && array_key_exists("customer_passport", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['passport'] === $_GET['customer_passport'];
            }


            if ($printThis && array_key_exists('price', $_GET)) {
                $printThis = $purchasedItem['price'] === (int)$_GET['price'];
            }
            if ($printThis && array_key_exists('purchased_item_id', $_GET)) {
                $printThis = $purchasedItem['purchased_item_id'] === (int)$_GET['purchased_item_id'];
            }
            if ($printThis && array_key_exists('pass_id', $_GET)) {
                $printThis = $purchasedItem['pass_id'] === (int)$_GET['pass_id'];
            }
            if ($printThis && array_key_exists('id_programme', $_GET)) {
                $printThis = $purchasedItem['id_programme'] === (int)$_GET['id_programme'];
            }
            if ($printThis) {
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

    // Начало валидации параметров по клиенту
    $paramTypeValidationResult = paramTypeValidation('customer_id', $_GET, 'incorrect customer_id');
    $paramTypeValidationResult = paramTypeValidation('customer_full_name', $_GET, 'incorrect customer_full_name');
    $paramTypeValidationResult = paramTypeValidation('customer_sex', $_GET, 'incorrect customer_sex');
    $paramTypeValidationResult = paramTypeValidation('customer_birthdate', $_GET, 'incorrect customer_birthdate');
    $paramTypeValidationResult = paramTypeValidation('customer_phone', $_GET, 'incorrect customer_phone');
    $paramTypeValidationResult = paramTypeValidation('customer_passport', $_GET, 'incorrect customer_passport');
    // Конец валидации параметров по клиенту

    if (null === $paramTypeValidationResult) {
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
                $printThis = $customerId === (int)$_GET['customer_id'];
            } else {
                $printThis = true;
            }
            if ($printThis && array_key_exists("customer_full_name", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['full_name'] === $_GET['customer_full_name'];
            }
            if ($printThis && array_key_exists("customer_sex", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['sex'] === $_GET['customer_sex'];
            }
            if ($printThis && array_key_exists("customer_birthdate", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['birthdate'] === $_GET['customer_birthdate'];
            }
            if ($printThis && array_key_exists("customer_phone", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['phone'] === $_GET['customer_phone'];
            }
            if ($printThis && array_key_exists("customer_passport", $_GET)) {
                $printThis = $customerIdToInfo[$customerId]['passport'] === $_GET['customer_passport'];
            }


            if ($printThis && array_key_exists('price', $_GET)) {
                $printThis = $purchasedItem['price'] === (int)$_GET['price'];
            }
            if ($printThis && array_key_exists('purchased_item_id', $_GET)) {
                $printThis = $purchasedItem['purchased_item_id'] === (int)$_GET['purchased_item_id'];
            }
            if ($printThis && array_key_exists('pass_id', $_GET)) {
                $printThis = $purchasedItem['pass_id'] === (int)$_GET['pass_id'];
            }
            if ($printThis && array_key_exists('id_programme', $_GET)) {
                $printThis = $purchasedItem['id_programme'] === (int)$_GET['id_programme'];
            }
            if ($printThis) {
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
    return $result;
}