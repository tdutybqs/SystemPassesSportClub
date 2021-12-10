<?php

include_once __DIR__ . "/../Infrastructure/generalFunctions.php";
require_once __DIR__ . "/../Entity/CustomerView.php";
require_once __DIR__ . "/../Entity/PurchasedItem.php";
require_once __DIR__ . "/../Entity/Programme.php";

require_once __DIR__."/../Infrastructure/Logger/LoggerInterface.php";
require_once __DIR__ . "/../Infrastructure/AppConfig.php";

/**
 * Поиск покупаемых программ пользователем
 * @param array request - параметры, которые передает пользователь
 * @param LoggerInterface $logger -  компонент, отвечающий за логирование
 * @param AppConfig $appConfig - конфиг приложения
 * @return array
 * @throws JsonException
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
    $customerResult = [];

    $passes = loadData($appConfig->getPathToPass());
    $customers = loadData($appConfig->getPathToCustomers());
    $purchasedItems = loadData($appConfig->getPathToPurchasedItems());
    $programmes = loadData($appConfig->getPathToProgrammes());

    $logger->log('Переход на /purchased_items выполнен');

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
            $customerIdToInfo[$currentCustomer['customer_id']] = CustomerView::createFromArray($currentCustomer);
        }
        $customerList = [];
        $customerIdToPurchasedItem = [];
        $passesIdToInfo = [];
        foreach ($passes as $passInfo) {
            $passInfo['customer'] = $customerIdToInfo[$passInfo['customer_id']];
            $passesIdToInfo[$passInfo['pass_id']] = Pass::createFromArray($passInfo);
        }

        $programmesIdToInfo = [];
        foreach ($programmes as $programme) {
            $programmesIdToInfo[$programme['id_programme']] = Programme::createFromArray($programme);
        }

        foreach ($purchasedItems as $purchasedItem) {
            $customerId = $passesIdToInfo[$purchasedItem['pass_id']]->getCustomer()->getId();

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

            if ($searchCriteriaMet) {
                // Если такого кастомера еще нет в массиве, добавляем по id кастомера флаг true
                if (!array_key_exists($customerId, $customerResult)) {
                    $customerResult[$customerId] = true;
                    $customerList[] = $customerIdToInfo[$customerId];
                }

                if (!array_key_exists($customerId, $customerIdToPurchasedItem)) {
                    $customerIdToPurchasedItem[$customerId] = [];
                }
                $purchasedItem['id_programme'] = $programmesIdToInfo[$purchasedItem['id_programme']];
                $purchasedItem['pass_id'] = $passesIdToInfo[$purchasedItem['pass_id']];
                $customerIdToPurchasedItem[$customerId][] = PurchasedItem::createFromArray($purchasedItem);
            }
        }
        //customerList - список всех клиентов, которые нам подходят
        //customerIdToPurchasedItem - список всех purchased_item, которые нам подходят. Ключ - id customer
        foreach ($customerList as &$currentCustomer) {
            $currentCustomer->setPurchasedItems($customerIdToPurchasedItem[$currentCustomer->getId()]);
        }
        $result = $customerList;

        $logger->log('Найдено ' . count($result) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $result
        ];
    }
    return $result;
};