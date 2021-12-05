<?php

require_once __DIR__ . "/../Classes/BenefitPass.php";
require_once __DIR__ . "/../Classes/Customer.php";
include_once __DIR__ . "/../Functions/generalFunctions.php";

/**
 * Функция обработки поиска льгот
 *
 * @param array request - параметры, которые передает пользователь
 * @param callable $logger -  функция, инкапсулирующая логику логгера
 * @return array
 * @throws JsonException
 */
return static function (array $request, callable $logger): array {
    $logger('Переход на: /benefit_pass выполнен.');

    $benefitPasses = loadData(__DIR__ . "/../../Jsons/benefit_pass.json");
    $customers = loadData(__DIR__ . "/../../Jsons/customers.json");

    $findCustomers = [];
    $paramsValidation = [
        'sex' => 'incorrect param "sex"',
        'birthdate' => 'incorrect param "birthdate"',
        'phone' => 'incorrect param "phone"',
        'passport' => 'incorrect param "passport"',
        'customer_id' => 'incorrect param "customer_id"',
        'full_name' => 'incorrect param "full_name"',
        'type_benefit' => 'incorrect param "type_benefit"',
        'number_document' => 'incorrect param "number_document"',
        'end' => 'incorrect param "end"',
    ];

    if (null === ($result = paramTypeValidation($paramsValidation, $request))) {
        $customerIdToBenefitPass = [];
        foreach ($benefitPasses as $benefitPass) {
            $benefitPassObj = new BenefitPass();
            $benefitPassObj->setTypeBenefit($benefitPass['type_benefit'])
                ->setNumberDocument($benefitPass['number_document'])
                ->setEnd($benefitPass['end']);
            $customerIdToBenefitPass[$benefitPass['customer_id']] = $benefitPassObj;
        }

        foreach ($customers as $customer) {
            // Trash
            if ($customerIdToBenefitPass[$customer['customer_id']] === null) {
                continue;
            }
            $benefitPassObjToArray = [
                'type_benefit' => $customerIdToBenefitPass[$customer['customer_id']]->getTypeBenefit(),
                'number_document' => $customerIdToBenefitPass[$customer['customer_id']]->getNumberDocument(),
                'end' => $customerIdToBenefitPass[$customer['customer_id']]->getEnd()
            ];
            $benefitPassPurchaseReportsMeetSearchCriteria = checkCriteria($request, array_merge($customer, $benefitPassObjToArray));
            // End Trash

            if ($benefitPassPurchaseReportsMeetSearchCriteria && $customerIdToBenefitPass[$customer["customer_id"]] !== null) {
                $customerObj = new Customer();
                $customerObj->setBirthdate($customer['birthdate'])
                    ->setId($customer['customer_id'])
                    ->setFullName($customer['full_name'])
                    ->setPassport($customer['passport'])
                    ->setPhone($customer['phone'])
                    ->setSex($customer['sex']);
                $complete = $customerIdToBenefitPass[$customer["customer_id"]]->setCustomer($customerObj);

                $findCustomers[] = $complete;
            }
        }
        $logger('Найдено ' . count($findCustomers) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findCustomers
        ];
    }
    return $result;
};