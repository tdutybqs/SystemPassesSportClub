<?php

require_once __DIR__ . "/Customer.php";
require_once __DIR__ . "/Employee.php";

/**
 * Абонементы
 */
class Pass implements JsonSerializable
{
    /**
     * id абонемента
     * @var int
     */
    private int $id;

    /**
     * срок работы абонемента
     * @var string
     */
    private string $duration;

    /**
     * скидка абонемента
     * @var string
     */
    private string $discount;

    /**
     * ID клиента
     * @var Customer
     */
    private Customer $customer;

    /**
     * ID сотрудника (кто продал)
     * @var Employee
     */
    private Employee $employee;

    /**
     * Получить id абонемента
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Установить id абонемента
     * @param int $id
     * @return Pass
     */
    public function setId(int $id): Pass
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Получить срок действия абонемента
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * Установить срок действия абонемента
     * @param string $duration
     * @return Pass
     */
    public function setDuration(string $duration): Pass
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Получить размер скидки
     * @return string
     */
    public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Установить размер скидки
     * @param string $discount
     * @return Pass
     */
    public function setDiscount(string $discount): Pass
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Получить id клиента
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * Установить клиента
     * @param Customer $customer
     * @return Pass
     */
    public function setCustomer(Customer $customer): Pass
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Получить id сотрудника, который продал абонемент
     * @return Employee
     */
    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    /**
     * Установить id сотрудника, который продал абонемент
     * @param Employee $employee
     * @return Pass
     */
    public function setEmployee(Employee $employee): Pass
    {
        $this->employee = $employee;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            "pass_id" => $this->getId(),
            "customer_id" => $this->getCustomer()->getId(),
            "duration" => $this->getDuration(),
        ];
    }

}