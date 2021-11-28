<?php
$pathToBenefitPass = __DIR__."/Jsons/benefit_pass.json";
$benefitPassTxt = file_get_contents($pathToBenefitPass);
$benefitPasses = json_decode($benefitPassTxt, true);

$pathToCustomers= __DIR__."/Jsons/customers.json";
$customersTxt = file_get_contents($pathToCustomers);
$customers = json_decode($customersTxt, true);


if ('/pass' === $_SERVER['PATH_INFO']){
    $httpCode = 200;
    $result = [];
    // Выводит людей без льготного абонемента. Доделать
    $benefitPassIdToInfo = [];
    foreach ($benefitPasses as $benefitPass){
        $benefitPassIdToInfo[$benefitPass['pass_id']] = $benefitPass;
    }

    $customerIdToInfo = [];
    foreach ($customers as $customerInfo){
        $customerIdToInfo[$customerInfo['customer_id']] = $customerInfo;
    }

    foreach ($customers as $customer){
        if (array_key_exists("sex", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['sex'] ==
                $customerIdToInfo[$customer["customer_id"]]["sex"];
        }else{
            $benefitPassPurchaseReportsMeetSearchCriteria = true;
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("customer_id", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['customer_id'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["customer_id"];
        }


        if ($benefitPassPurchaseReportsMeetSearchCriteria){
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
else {
    //unsupported answer
    $httpCode = 404;
    $result = [
        'message' => 'unsupported request',
        'status' => 'fail',
    ];
}
header('Content-Type: application/json');
http_response_code($httpCode);
echo json_encode($result);
