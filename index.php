<?php
$pathToBenefitPass = __DIR__."/Jsons/benefit_pass.json";
$benefitPassTxt = file_get_contents($pathToBenefitPass);
$benefitPasses = json_decode($benefitPassTxt, true);

$pathToCustomers= __DIR__."/Jsons/customers.json";
$customersTxt = file_get_contents($pathToCustomers);
$customers = json_decode($customersTxt, true);

$pathToProgrammes = __DIR__."/Jsons/programmes.json";
$programmesTxt = file_get_contents($pathToProgrammes);
$programmes = json_decode($programmesTxt, true);

$pathToPasses = __DIR__."/Jsons/pass.json";
$passesTxt = file_get_contents($pathToPasses);
$passes = json_decode($passesTxt, true);

if ('/benefit_pass' === $_SERVER['PATH_INFO']){
    $httpCode = 200;
    $result = [];
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
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("birthdate", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['birthdate'] ==
                $customerIdToInfo[$customer["customer_id"]]["birthdate"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("phone", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['phone'] ==
                $customerIdToInfo[$customer["customer_id"]]["phone"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("passport", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['passport'] ==
                $customerIdToInfo[$customer["customer_id"]]["passport"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("customer_id", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['customer_id'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["customer_id"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("full_name", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['full_name'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["full_name"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("type_benefit", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['type_benefit'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["type_benefit"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("number_document", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['number_document'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["number_document"];
        }
        if ($benefitPassPurchaseReportsMeetSearchCriteria && array_key_exists("end", $_GET)) {
            $benefitPassPurchaseReportsMeetSearchCriteria = $_GET['end'] ==
                $benefitPassIdToInfo[$customer["customer_id"]]["end"];
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
else if ('/programmes' === $_SERVER['PATH_INFO'])
{
    $httpCode = 200;
    $result = [];

    foreach ($programmes as $programme){
        if (array_key_exists("id_programme", $_GET)){
            $printThis = $programme['id_programme'] == $_GET['id_programme'];
        } else {
            $printThis = true;
        }
        if ($printThis && array_key_exists("name", $_GET)){
            $printThis = $programme['name'] == $_GET['name'];
        }
        if ($printThis && array_key_exists("duration", $_GET)){
            $printThis = $programme['duration'] == $_GET['duration'];
        }
        if ($printThis && array_key_exists("discount", $_GET)){
            $printThis = $programme['discount'] == $_GET['discount'];
        }
        if ($printThis){
            $result[] = $programme;
        }
    }
}
else if ('/pass' === $_SERVER['PATH_INFO']){
    $httpCode = 200;
    $result = [];

    foreach ($passes as $pass){
        if (array_key_exists("pass_id", $_GET)){
            $printThis = $pass['pass_id'] == $_GET['pass_id'];
        } else {
            $printThis = true;
        }
        if ($printThis && array_key_exists("duration", $_GET)){
            $printThis = $pass['duration'] == $_GET['duration'];
        }
        if ($printThis){
            $result[] = $pass;
        }
    }
}
else if ('/passf' === $_SERVER['PATH_INFO']){

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
