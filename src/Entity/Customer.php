<?php

require_once __DIR__ . "/AbstractUser.php";
require_once __DIR__ . "/../Infrastructure/InvalidFilePath.php";
require_once __DIR__ . "/../Infrastructure/InvalidDataStructureException.php";

/**
 * Клиент
 */
class Customer extends AbstractUser
{
    /**
     * Конструктор клиента
     * @param int $id - идентификатор клиента
     * @param string $full_name - ФИО клиента
     * @param string $phone - телефон клиента
     * @param string $birthdate - дата рождения клиента
     * @param string $passport - пасспортные данные клиента
     * @param string $sex - пол
     */
    public function __construct(
        int $id,
        string $full_name,
        string $phone,
        string $birthdate,
        string $passport,
        string $sex
    ) {
        parent::__construct($id, $full_name, $phone);
        $this->birthdate = $birthdate;
        $this->passport = $passport;
        $this->sex = $sex;
    }

    /**
     * Купленные пурчейзы
     * @var array
     */
    private array $purchasedItems;

    /**
     * Получить пурчейзы
     * @return array
     */
    public function getPurchasedItems(): array
    {
        return $this->purchasedItems;
    }

    /**
     * Установить пурчейзы
     * @param array $purchasedItems
     * @return Customer
     */
    public function setPurchasedItems(array $purchasedItems): Customer
    {
        $this->purchasedItems = $purchasedItems;
        return $this;
    }

    /**
     * пол клиента
     * @var string
     */
    private string $sex;

    /**
     * д.р. клиента
     * @var string
     */
    private string $birthdate;

    /**
     * паспорт клиента
     * @var string
     */
    private string $passport;

    /**
     * Получить пол клиента
     * @return string
     */
    final public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * Получить дату рождения клиента
     * @return string
     */
    final public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    /**
     * Получить пасспортные данные клиента
     * @return string
     */
    public function getPassport(): string
    {
        return $this->passport;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "customer_id" => $this->getId(),
            "full_name" => $this->getFullName(),
            "sex" => $this->getSex(),
            "birthdate" => $this->getBirthdate(),
            "phone" => $this->getPhone(),
            "passport" => $this->getPassport()
        ];
    }

    /**
     * Создание объекта из массива
     * @param array $data
     * @return Customer
     * @throws InvalidDataStructureException - некорректная структура файла
     */
    public static function createFromArray(array $data): Customer
    {
        $requiredFields = [
            'customer_id',
            'full_name',
            'sex',
            'birthdate',
            'phone',
            'passport'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new static($data['customer_id'], $data['full_name'], $data['phone'], $data['birthdate'],
            $data['passport'], $data['sex']);
    }
}