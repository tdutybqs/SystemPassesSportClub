<?php

include_once __DIR__ . "/../Functions/generalFunctions.php";
require_once __DIR__ . "/../Classes/Customer.php";
require_once __DIR__ . "/../Classes/PurchasedItem.php";
require_once __DIR__ . "/../Classes/Programme.php";


/**
 * Поиск покупаемых программ пользователем
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array {
    $customerResult = [];

    $passes = loadData(__DIR__ . "/../../Jsons/pass.json");
    $customers = loadData(__DIR__ . "/../../Jsons/customers.json");
    $purchasedItems = loadData(__DIR__ . "/../../Jsons/purchased_item.json");
    $programmes = loadData(__DIR__ . "/../../Jsons/programmes.json");

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
        foreach ($customers as $currentCustomer) {
            $customerObj = new Customer();
            $customerObj->setId($currentCustomer['customer_id'])
                ->setSex($currentCustomer['sex'])
                ->setPhone($currentCustomer['phone'])
                ->setPassport($currentCustomer['passport'])
                ->setFullName($currentCustomer['full_name'])
                ->setBirthdate($currentCustomer['birthdate']);
            $customerIdToInfo[$currentCustomer['customer_id']] = $customerObj;
        }

        $customerIdToPurchasedItem = [];
        $passesIdToInfo = [];
        foreach ($passes as $passInfo) {
            $passObj = new Pass();
            $passObj->setId($passInfo['pass_id'])
                ->setDiscount($passInfo['discount'])
                ->setDuration($passInfo['duration'])
                ->setCustomer($customerIdToInfo[$passInfo['customer_id']]);
            $passesIdToInfo[$passInfo['pass_id']] = $passObj;
        }

        $programmesIdToInfo = [];
        foreach ($programmes as $programme) {
            $programmeObj = new Programme();
            $programmeObj->setId($programme['id_programme'])
                ->setDuration($programme['duration'])
                ->setDiscount($programme['discount'])
                ->setName($programme['name']);
            $programmesIdToInfo[$programme['id_programme']] = $programmeObj;
        }

        foreach ($purchasedItems as $purchasedItem) {
            $customerId = $passesIdToInfo[$purchasedItem['pass_id']]->getCustomer()->getId();

            // Trash
            if ($customerIdToInfo[$customerId] === null) {
                continue;
            }
            $customerInfo = [
                "customer_id" => $customerIdToInfo[$customerId]->getId(),
                "full_name" => $customerIdToInfo[$customerId]->getFullName(),
                "sex" => $customerIdToInfo[$customerId]->getSex(),
                "birthdate" => $customerIdToInfo[$customerId]->getBirthdate(),
                "phone" => $customerIdToInfo[$customerId]->getPhone(),
                "passport" => $customerIdToInfo[$customerId]->getPassport()
            ];
            $searchCriteriaMet = checkCriteria($request, array_merge($purchasedItem, $customerInfo));
            // End Trash

            if ($searchCriteriaMet) {
                // Если такого кастомера еще нет в массиве, добавляем по id кастомера флаг true
                if (!array_key_exists($customerId, $customerResult)) {
                    $customerResult[$customerId] = true;
                    $result[] = $customerIdToInfo[$customerId];
                }

                if (!array_key_exists($customerId, $customerIdToPurchasedItem)) {
                    $customerIdToPurchasedItem[$customerId] = [];
                }
                $purchasedItemObj = new PurchasedItem();
                $purchasedItemObj->setPurchasedItemId($purchasedItem['purchased_item_id'])
                    ->setPrice($purchasedItem['price'])
                    ->setProgramId($programmesIdToInfo[$purchasedItem['id_programme']])
                    ->setPassId($passesIdToInfo[$purchasedItem['pass_id']]);
                $customerIdToPurchasedItem[$customerId][] = $purchasedItemObj;
            }
        }
        $result = $customerIdToPurchasedItem;

        $logger('Найдено ' . count($result) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $result
        ];
    }
    return $result;
};