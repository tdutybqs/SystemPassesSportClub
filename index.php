<?php
$pathToCustomers = __DIR__ . '/Jsons/customers.json';
$customersTxt = file_get_contents($pathToCustomers);
$customers = json_decode($customersTxt, true);

$customerIdToInfo = [];
foreach ($customers as $customer => $customer){
    $customerIdToInfo[$customer['id']] = $customer;
}

$pathToPasses = __DIR__ . '/Jsons/passes.json';
$passesTxt = file_get_contents($pathToPasses);
$passes = json_decode($passesTxt, true);

if ('/passes' === $_SERVER['PATH_INFO']) {
    $customerIdToInfo = [];
    foreach ($customers as $customer){
        $customerIdToInfo[$author['id']] = $customer;
    }
}
$httpCode = 200;
$result = [];