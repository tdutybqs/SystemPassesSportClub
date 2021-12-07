<?php

/**
 * Конфиг приложения
 */
class AppConfig
{
    /**
     * Путь до файла с данными о льготах
     * @var string
     */
    private string $pathToBenefitPass = __DIR__ . "/../../Jsons/benefit_pass.json";

    /**
     * Путь до файла с данными о купленных пурчейзах
     * @var string
     */
    private string $pathToPurchasedItems = __DIR__ . "/../../Jsons/purchased_item.json";

    /**
     * Путь до файла с данным о клиентах
     * @var string
     */
    private string $pathToCustomers = __DIR__ . "/../../Jsons/customers.json";

    /**
     * Путь до файла с данными об абонементах
     * @var string
     */
    private string $pathToPass = __DIR__ . "/../../Jsons/pass.json";

    /**
     * Путь до файла с данными о программах
     * @var string
     */
    private string $pathToProgrammes = __DIR__ . "/../../Jsons/programmes.json";


    /** Получить путь до файла с данными о льготах
     * @return string
     */
    public function getPathToBenefitPass(): string
    {
        return $this->pathToBenefitPass;
    }

    /**
     * Установить путь до файла с данными о льготах
     * @param string $pathToBenefitPass
     * @return AppConfig
     */
    public function setPathToBenefitPass(string $pathToBenefitPass): AppConfig
    {
        $this->pathToBenefitPass = $pathToBenefitPass;
        return $this;
    }

    /**
     * Получить путь до файла с данными о клиентах
     * @return string
     */
    public function getPathToCustomers(): string
    {
        return $this->pathToCustomers;
    }

    /**
     * Установить путь до файла с данными о клиентах
     * @param string $pathToCustomers
     * @return AppConfig
     */
    public function setPathToCustomers(string $pathToCustomers): AppConfig
    {
        $this->pathToCustomers = $pathToCustomers;
        return $this;
    }

    /**
     * Получить путь до файла с данными об абонементах
     * @return string
     */
    public function getPathToPass(): string
    {
        return $this->pathToPass;
    }

    /**
     * Установить путь до файла с данными об абонементах
     * @param string $pathToPass
     * @return AppConfig
     */
    public function setPathToPass(string $pathToPass): AppConfig
    {
        $this->pathToPass = $pathToPass;
        return $this;
    }

    /**
     * Получить путь до файла с данными о программах
     * @return string
     */
    public function getPathToProgrammes(): string
    {
        return $this->pathToProgrammes;
    }

    /**
     * Установить путь до файла с данными о программах
     * @param string $pathToProgrammes
     * @return AppConfig
     */
    public function setPathToProgrammes(string $pathToProgrammes): AppConfig
    {
        $this->pathToProgrammes = $pathToProgrammes;
        return $this;
    }

    /**
     * Получить путь до файла с купленными пурчейзами
     * @return string
     */
    public function getPathToPurchasedItems(): string
    {
        return $this->pathToPurchasedItems;
    }

    /**
     * Установить путь до файла с купленными пурчейзами
     * @param string $pathToPurchasedItems
     * @return AppConfig
     */
    public function setPathToPurchasedItems(string $pathToPurchasedItems): AppConfig
    {
        $this->pathToPurchasedItems = $pathToPurchasedItems;
        return $this;
    }
}