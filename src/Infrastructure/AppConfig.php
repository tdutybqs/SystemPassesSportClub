<?php

require_once __DIR__."/../Infrastructure/InvalidDataStructureException.php";
require_once __DIR__."/../Infrastructure/InvalidFilePath.php";

/**
 * Конфиг приложения
 */
class AppConfig
{
    /**
     * Путь до файла с данными о сотрудниках
     * @var string
     */
    private string $pathToEmployees = __DIR__ . "/../../data/employees.json";

    /**
     * Путь до файла с данными о льготах
     * @var string
     */
    private string $pathToBenefitPass = __DIR__ . "/../../data/benefit_pass.json";

    /**
     * Путь до файла с данными о купленных пурчейзах
     * @var string
     */
    private string $pathToPurchasedItems = __DIR__ . "/../../data/purchased_item.json";

    /**
     * Путь до файла с данным о клиентах
     * @var string
     */
    private string $pathToCustomers = __DIR__ . "/../../data/customers.json";

    /**
     * Путь до файла с данными об абонементах
     * @var string
     */
    private string $pathToPass = __DIR__ . "/../../data/pass.json";

    /**
     * Путь до файла с данными о программах
     * @var string
     */
    private string $pathToProgrammes = __DIR__ . "/../../data/programmes.json";

    /**
     * Получить путь до файла с данными о сотрудниках
     * @return string
     */
    public function getPathToEmployees(): string
    {
        return $this->pathToEmployees;
    }

    /**
     * Установить путь до файла с данными о сотрудниках
     * @param string $pathToEmployees
     * @return AppConfig
     * @throws Exception - данные некорректны
     */
    private function setPathToEmployees(string $pathToEmployees): AppConfig
    {
        $this->validateFilePath($pathToEmployees);
        $this->pathToEmployees = $pathToEmployees;
        return $this;
    }


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
     * @throws Exception - данные некорректны
     */
    private function setPathToBenefitPass(string $pathToBenefitPass): AppConfig
    {
        $this->validateFilePath($pathToBenefitPass);
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
     * @throws Exception - данные некорректны
     */
    private function setPathToCustomers(string $pathToCustomers): AppConfig
    {
        $this->validateFilePath($pathToCustomers);
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
     * @throws Exception - данные некорректны
     */
    private function setPathToPass(string $pathToPass): AppConfig
    {
        $this->validateFilePath($pathToPass);
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
     * @throws Exception - данные некорректны
     */
    private function setPathToProgrammes(string $pathToProgrammes): AppConfig
    {
        $this->validateFilePath($pathToProgrammes);
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
     * @throws Exception - данные некорректны
     */
    private function setPathToPurchasedItems(string $pathToPurchasedItems): AppConfig
    {
        $this->validateFilePath($pathToPurchasedItems);
        $this->pathToPurchasedItems = $pathToPurchasedItems;
        return $this;
    }

    /**
     * Валидация пути до файла
     * @param string $path
     * @return void
     * @throws Exception - если файл не найден
     */
    private function validateFilePath(string $path): void
    {
        if (false === file_exists($path)) {
            throw new InvalidFilePath('Некорректный путь до файла с данными');
        }
    }

    /**
     * Создает конфиг приложения из массива
     * @param array $config
     * @return static
     * @uses AppConfig::setPathToBenefitPass()
     * @uses \AppConfig::setPathToCustomers()
     * @uses \AppConfig::setPathToPass()
     * @uses \AppConfig::setPathToProgrammes()
     * @uses \AppConfig::setPathToPurchasedItems()
     * @uses \AppConfig::setPathToEmployees()
     */
    public static function createFromArray(array $config): self
    {
        $appConfig = new self();
        foreach ($config as $key => $value) {
            if (property_exists($appConfig, $key)) {
                $setter = 'set' . ucfirst($key);
                $appConfig->{$setter}($value);
            }
        }
        return $appConfig;
    }
}