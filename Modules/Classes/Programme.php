<?php

/**
 * Программы
 */
class Programme implements JsonSerializable
{
    /**
     * id программы
     * @var int
     */
    private int $id;

    /**
     * название программы
     * @var string
     */
    private string $name;

    /**
     * срок работы программы
     * @var string
     */
    private string $duration;

    /**
     * Уровень сложности программы
     * @var string
     */
    private string $discount;

    /**
     * клиент программы
     * @var Customer
     */
    private Customer $customer;

    /**
     * Получить id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Установить id
     * @param int $id
     * @return Programme
     */
    public function setId(int $id): Programme
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Получить название программы
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Установить название программы
     * @param string $name
     * @return Programme
     */
    public function setName(string $name): Programme
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Получить срок действия программы
     * @return string
     */
    public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * Установить срок действия программы
     * @param string $duration
     * @return Programme
     */
    public function setDuration(string $duration): Programme
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Получить скидку программы
     * @return string
     */
    public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Установить скидку программы
     * @param string $discount
     * @return Programme
     */
    public function setDiscount(string $discount): Programme
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id_programme' => $this->getId(),
            'name' => $this->getName(),
            'duration' => $this->getDuration(),
            'discount' => $this->getDiscount()
        ];
    }
}