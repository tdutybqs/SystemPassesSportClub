<?php

return [
    /**
     * Путь до файла с данными о льготных абонементах
     */
    'pathToBenefitPass' => __DIR__ . "/../../data/benefit_pass.json",

    /**
     * Путь до файла с данными об абонементах
     */
    'pathToPass' => __DIR__ . "/../../data/pass.json",

    /**
     * Путь до файла с данными о клиентах
     */
    'pathToCustomers' => __DIR__ . "/../../data/customers.json",

    /**
     * Путь до файла с сотрудниками
     */
    'pathToEmployees' => __DIR__ . "/../../data/employees.json",

    /**
     * Путь до файла с программами
     */
    'pathToProgrammes' => __DIR__ . "/../../data/programmes.json",

    /**
     * Путь до файла с купленными пурчейзами
     */
    'pathToPurchasedItems' => __DIR__ . "/../../data/purchased_item.json",

    /**
     * Путь до файла с логами
     */
    'pathToLogFile' => __DIR__ . "/../../var/log/app.log",

    /**
     * Тип, использующегося логера
     */
    'loggerType' => 'fileLogger',
];