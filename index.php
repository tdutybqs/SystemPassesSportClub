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

if ('/benefit_pass' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [];
    // ХешМапа льгот
    $benefitPassIdToInfo = [];
    foreach ($benefitPasses as $benefitPass) {
        $benefitPassIdToInfo[$benefitPass['pass_id']] = $benefitPass;
    }

    // ХешМапа клиентов
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
        // TODO вернуть плюсики
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
        // TODO удалить
        if (!$benefitPassIdToInfo[$customer["customer_id"]]) {
            $benefitPassPurchaseReportsMeetSearchCriteria = false;
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria) {
            $benefitPass = $benefitPassIdToInfo[$customer["customer_id"]];
            $customer["benefit"] = $benefitPass;
            unset($customer["benefit"]["pass_id"]);
            unset($customer["benefit"]["discount"]);
            unset($customer["benefit"]["customer_id"]);
            unset($customer["benefit"]["duration"]);
            $result[] = $customer;
        }
    }
} elseif ('/programmes' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [];

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
} elseif ('/pass' === $_SERVER['PATH_INFO']) {
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
} else if ('/purchased_items' === $_SERVER['PATH_INFO']) {
    $httpCode = 200;
    $result = [];
    $customerResult = [];

    $customerIdToInfo = [];
    foreach ($customers as $customerInfo) {
        $customerIdToInfo[$customerInfo['customer_id']] = $customerInfo;
    }
    // TODO pass.json добавлен 4 и 5 пасс ид
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
        if ($printThis) {
            if (!array_key_exists($customerId, $customerResult)) {
                $customerResult[$customerId] = true;
                $result[] = $customerIdToInfo[$customerId];
            }
            // мапа
            if (!array_key_exists($customerId, $customerIdToPurchasedItem)) {
                $customerIdToPurchasedItem[$customerId] = [];
            }
            $customerIdToPurchasedItem[$customerId][] = $purchasedItem;
            // конец
        }
    }
    foreach ($result as &$customerInfo) {
        $customerInfo['purchased_items'] = $customerIdToPurchasedItem[$customerInfo['customer_id']];
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

