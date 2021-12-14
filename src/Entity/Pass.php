<?php

namespace EfTech\SportClub\Entity;
require_once __DIR__ . "/Customer.php";
require_once __DIR__ . "/Employee.php";
require_once __DIR__ . "/../Infrastructure/InvalidDataStructureException.php";

use Exception;
use EfTech\SportClub\Infrastructure\InvalidDataStructureException;
use JsonSerializable;

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
     * Конструктор абонемент
     * @param int $id - идентификатор абонемента
     * @param string $duration - период действия
     * @param string $discount - скидка
     * @param Customer $customer - кому принадлежит абонемент (клиент)
     */
    public function __construct(int $id, string $duration, string $discount, Customer $customer)
    {
        $this->id = $id;
        $this->duration = $duration;
        $this->discount = $discount;
        $this->customer = $customer;
    }

    /**
     * Получить id абонемента
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить срок действия абонемента
     * @return string
     */
    final public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * Получить размер скидки
     * @return string
     */
    final public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Получить id клиента
     * @return Customer
     */
    final public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            "pass_id" => $this->getId(),
            "customer_id" => $this->getCustomer()->getId(),
            "duration" => $this->getDuration(),
        ];
    }

    /**
     * Создание объекта из массива
     * @param array $data
     * @return Pass
     * @throws Exception - некорректная структура файла
     */
    public static function createFromArray(array $data): Pass
    {
        $requiredFields = [
            'pass_id',
            'duration',
            'discount',
            'customer'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0){
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new Pass($data['pass_id'], $data['duration'], $data['discount'], $data['customer']);
    }

}