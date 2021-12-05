<?php

require_once __DIR__ . "/Programme.php";
require_once __DIR__ . "/Pass.php";

/**
 * Купленные продукта
 *
 */
class PurchasedItem extends Programme
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
     * Получить id купл программы
     * @return int
     */
    public function getPurchasedItemId(): int
    {
        return $this->purchasedItemId;
    }

    /**
     * Установить id купл программы
     * @param int $purchasedItemId
     * @return PurchasedItem
     */
    public function setPurchasedItemId(int $purchasedItemId): PurchasedItem
    {
        $this->purchasedItemId = $purchasedItemId;
        return $this;
    }

    /**
     * Получить id абонемента
     * @return Pass
     */
    public function getPassId(): Pass
    {
        return $this->passId;
    }

    /**
     * Установить id абонемента
     * @param Pass $passId
     * @return PurchasedItem
     */
    public function setPassId(Pass $passId): PurchasedItem
    {
        $this->passId = $passId;
        return $this;
    }

    /**
     * Получить id программы
     * @return Programme
     */
    public function getProgramId(): Programme
    {
        return $this->programId;
    }

    /**
     * Установить id программы
     * @param Programme $programId
     * @return PurchasedItem
     */
    public function setProgramId(Programme $programId): PurchasedItem
    {
        $this->programId = $programId;
        return $this;
    }


    /**
     * Получить стоимость
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * Установить стоимость
     * @param int $price
     * @return PurchasedItem
     */
    public function setPrice(int $price): PurchasedItem
    {
        $this->price = $price;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'purchased_items' => [
                'purchased_item_id' => $this->getPurchasedItemId(),
                'pass_id' => $this->getPassId()->getId(),
                'id_programme' => $this->getProgramId()->getId(),
                'price' => $this->getPrice(),
            ]
        ];
    }
}