<?php

namespace EfTech\SportClub\Entity;
require_once __DIR__ . "/Programme.php";
require_once __DIR__ . "/Pass.php";
require_once __DIR__ . "/../Infrastructure/InvalidDataStructureException.php";

use Exception;
use EfTech\SportClub\Infrastructure\InvalidDataStructureException;
use JsonSerializable;

/**
 * Купленные продукта
 *
 */
class PurchasedItem implements JsonSerializable
{
    /**
     * ID абонемента
     */
    private Pass $passId;

    /**
     * ID купл программы
     * @var int
     */
    private int $purchasedItemId;

    /**
     * ID программы
     * @var Programme
     */
    private Programme $programId;

    /**
     * Стоимость программы
     * @var int
     */
    private int $price;

    /**
     * Конструктор купленного итема
     * @param int $purchasedItemId - идентификатор пурчейза
     * @param Pass $passId - абонемент
     * @param Programme $programId - программа
     * @param int $price - цена
     */
    public function __construct(int $purchasedItemId, Pass $passId, Programme $programId, int $price)
    {
        $this->passId = $passId;
        $this->purchasedItemId = $purchasedItemId;
        $this->programId = $programId;
        $this->price = $price;
    }

    /**
     * Получить id купл программы
     * @return int
     */
    final public function getPurchasedItemId(): int
    {
        return $this->purchasedItemId;
    }

    /**
     * Получить id абонемента
     * @return Pass
     */
    final public function getPassId(): Pass
    {
        return $this->passId;
    }

    /**
     * Получить id программы
     * @return Programme
     */
    final public function getProgramId(): Programme
    {
        return $this->programId;
    }

    /**
     * Получить стоимость
     * @return int
     */
    final public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'purchased_item_id' => $this->getPurchasedItemId(),
            'pass_id' => $this->getPassId()->getId(),
            'id_programme' => $this->getProgramId()->getId(),
            'price' => $this->getPrice(),
        ];
    }

    /**
     * Создание объекта из массива
     * @param $data
     * @return static
     * @throws Exception - некорректная структура файла
     */
    public static function createFromArray($data): PurchasedItem
    {
        $requiredFields = [
            'purchased_item_id',
            'pass_id',
            'id_programme',
            'price'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0){
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new static($data['purchased_item_id'], $data['pass_id'], $data['id_programme'], $data['price']);
    }
}