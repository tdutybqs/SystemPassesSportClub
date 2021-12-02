<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";

/**
 * Поиск покупаемых программ пользователем
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array
{
    $customerResult = [];

    // Подключаем нужные JSON's
    $passes = loadData(__DIR__ . "/../../Jsons/pass.json");
    $customers = loadData(__DIR__ . "/../../Jsons/customers.json");
    $purchasedItems = loadData(__DIR__ . "/../../Jsons/purchased_item.json");

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
};