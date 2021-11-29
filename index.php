<?php
$pathToBenefitPass = __DIR__ . "/Jsons/benefit_pass.json";
$benefitPassTxt = file_get_contents($pathToBenefitPass);
$benefitPasses = json_decode($benefitPassTxt, true);

$pathToCustomers = __DIR__ . "/Jsons/customers.json";
$customersTxt = file_get_contents($pathToCustomers);
$customers = json_decode($customersTxt, true);

$pathToProgrammes = __DIR__ . "/Jsons/programmes.json";
$programmesTxt = file_get_contents($pathToProgrammes);
$programmes = json_decode($programmesTxt, true);

$pathToPasses = __DIR__ . "/Jsons/pass.json";
$passesTxt = file_get_contents($pathToPasses);
$passes = json_decode($passesTxt, true);

$pathToPurchasedItems = __DIR__ . "/Jsons/purchased_item.json";
$purchasedItemsTxt = file_get_contents($pathToPurchasedItems);
$purchasedItems = json_decode($purchasedItemsTxt, true);

$pathInfo = array_key_exists("PATH_INFO", $_SERVER) && $_SERVER["PATH_INFO"] ? $_SERVER["PATH_INFO"] : '';
$pathToLogs = __DIR__ . "/Logs/app.log";
file_put_contents($pathToLogs, "Выполнен запрос на: " . urldecode($_SERVER['REQUEST_URI']) . "\n", FILE_APPEND);


if ('/benefit_pass' === $pathInfo) {
    $httpCode = 200;
    $result = [];

    // Начинаем валидацию критериев поиска
    $searchParamCorrect = true;
    if (array_key_exists("sex", $_GET) && false === is_string($_GET['sex'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect sex customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("birthdate", $_GET) && false === is_string($_GET['birthdate'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect birthdate customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("phone", $_GET) && false === is_string($_GET['phone'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect phone customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("passport", $_GET) && false === is_string($_GET['passport'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect passport customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("customer_id", $_GET) && false === is_string($_GET['customer_id'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect customer_id customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("full_name", $_GET) && false === is_string($_GET['full_name'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect full_name customer'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("type_benefit", $_GET) && false === is_string($_GET['type_benefit'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect type_benefit'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("number_document", $_GET) && false === is_string($_GET['number_document'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect number_document benefit_pass'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("end", $_GET) && false === is_string($_GET['end'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect end benefit_pass'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    // Конец валидации

    if ($searchParamCorrect) {

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
    }
} elseif ('/programmes' === $pathInfo) {
    $httpCode = 200;
    $result = [];

    //Начинаем валидацию критериев поиска 3 сценария
    $searchParamCorrect = true;
    if (array_key_exists("id_programme", $_GET) && false === is_string($_GET['id_programme'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect id_programme'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("name", $_GET) && false === is_string($_GET['name'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect name programme'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("duration", $_GET) && false === is_string($_GET['duration'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect duration programme'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    if (array_key_exists("discount", $_GET) && false === is_string($_GET['discount'])) {
        $result = [
            'status' => 'fail',
            'message' => 'incorrect discount programme'
        ];
        $httpCode = 500;
        $searchParamCorrect = false;
    }
    // Конец валидации 3 сценария

    if ($searchParamCorrect) {
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
    }
} elseif ('/pass' === $pathInfo) {
    $httpCode = 200;
    $result = [];

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
} else if ('/purchased_items' === $pathInfo) {
    $httpCode = 200;
    $result = [];
    $customerResult = [];

    $customerIdToInfo = [];
    foreach ($customers as $customerInfo) {
        $customerIdToInfo[$customerInfo['customer_id']] = $customerInfo;
    }
    $customerIdToPurchasedItem = [];

    $passesIdToInfo = [];
    foreach ($passes as $passInfo) {
        $passesIdToInfo[$passInfo['pass_id']] = $passInfo;
        foreach ($purchasedItems as $purchasedItem) {
            $customerId = $passesIdToInfo[$purchasedItem['pass_id']]['customer_id'];

            // Начало валидации
            $searchParamCorrect = true;
            if (array_key_exists("customer_id", $_GET) && false === is_string($_GET['customer_id'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_id'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            if (array_key_exists("customer_full_name", $_GET) && false === is_string($_GET['customer_full_name'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_full_name'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            if (array_key_exists("customer_sex", $_GET) && false === is_string($_GET['customer_sex'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_sex'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            if (array_key_exists("customer_birthdate", $_GET) && false === is_string($_GET['customer_birthdate'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_birthdate'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            if (array_key_exists("customer_phone", $_GET) && false === is_string($_GET['customer_phone'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_phone'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            if (array_key_exists("customer_passport", $_GET) && false === is_string($_GET['customer_passport'])) {
                $result = [
                    'status' => 'fail',
                    'message' => 'incorrect customer_passport'
                ];
                $httpCode = 500;
                $searchParamCorrect = false;
            }
            // Конец валидации
            if ($searchParamCorrect) {
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
        }
    }
} else {
    $httpCode = 404;
    $result = [
        'message' => 'unsupported request',
        'status' => 'fail',
    ];
}
header('Content-Type: application/json');
http_response_code($httpCode);
echo json_encode($result);

