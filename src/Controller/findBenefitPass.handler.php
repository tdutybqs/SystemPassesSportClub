<?php

namespace Controller;

use EfTech\Sportclub\Entity\BenefitPass;
use EfTech\Sportclub\Entity\Customer;
use Exception;
use EfTech\Sportclub\Infrastructure\AppConfig;
use EfTech\Sportclub\Infrastructure\Logger\LoggerInterface;
use function EfTech\Sportclub\Infrastructure\loadData;
use function EfTech\Sportclub\Infrastructure\paramTypeValidation;

include_once __DIR__ . "/../Infrastructure/generalFunctions.php";
require_once __DIR__ . "/../Infrastructure/Logger/LoggerInterface.php";
require_once __DIR__ . "/../Entity/BenefitPass.php";
require_once __DIR__ . "/../Entity/Customer.php";
require_once __DIR__ . "/../Infrastructure/AppConfig.php";

/**
 * Функция обработки поиска льгот
 * @param array request - параметры, которые передает пользователь
 * @param LoggerInterface $logger -  компонент, отвечающий за логирование
 * @param AppConfig $appConfig
 * @return array
 * @throws Exception
 */
return static function (array $request, LoggerInterface $logger, AppConfig $appConfig): array {
    $logger->log('Переход на: /benefit_pass выполнен.');

    $benefitPasses = loadData($appConfig->getPathToBenefitPass());
    $customers = loadData($appConfig->getPathToCustomers());

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
        foreach ($customers as $customer) {

            $customerIdToBenefitPass = [];
            foreach ($benefitPasses as $benefitPass) {
                $benefitPass['customer'] = Customer::createFromArray($customer);
                $customerIdToBenefitPass[$benefitPass['customer_id']] = BenefitPass::createFromArray($benefitPass);
            }

            if (array_key_exists($customer['customer_id'], $customerIdToBenefitPass) && $customerIdToBenefitPass[$customer['customer_id']] === null) {
                continue;
            }

            $benefitPassObjToArray = [];
            if (array_key_exists($customer['customer_id'], $customerIdToBenefitPass)){
                $benefitPassObjToArray = [
                    'type_benefit' => $customerIdToBenefitPass[$customer['customer_id']]->getTypeBenefit(),
                    'number_document' => $customerIdToBenefitPass[$customer['customer_id']]->getNumberDocument(),
                    'end' => $customerIdToBenefitPass[$customer['customer_id']]->getEnd()
                ];
            }
            $benefitPassPurchaseReportsMeetSearchCriteria = \EfTech\SportClub\Infrastructure\checkCriteria($request,
                array_merge($customer, $benefitPassObjToArray));

            if ($benefitPassPurchaseReportsMeetSearchCriteria && $customerIdToBenefitPass[$customer["customer_id"]] !== null) {
                $findCustomers[] =  $customerIdToBenefitPass[$customer['customer_id']];
            }
        }

        $logger->log('Найдено ' . count($findCustomers) . ' объектов.');
        return [
            'httpCode' => 200,
            'result' => $findCustomers
        ];
    }
    return $result;
};