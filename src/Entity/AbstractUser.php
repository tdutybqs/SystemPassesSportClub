<?php

namespace EfTech\SportClub\Entity;

use JsonSerializable;

abstract class AbstractUser implements JsonSerializable
{

    /**
     * id пользователя
     *
     * @var int
     */
    private int $id;

    /**
     * ФИО пользователя
     *
     * @var string
     */
    private string $full_name;

    /**
     * Номер пользователя
     *
     * @var string
     */
    private string $phone;

    /**
     * Конструктор AbstractUser
     * @param int $id - идентификатор пользователя
     * @param string $fullName - ФИО пользователя
     * @param string $phone - телефон пользователя
     */
    public function __construct(int $id, string $fullName, string $phone)
    {
        $this->id = $id;
        $this->full_name = $fullName;
        $this->phone = $phone;
    }

    /**
     * Получить id пользователя
     *
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить ФИО пользователя
     *
     * @return string
     */
    final public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * Получить телефон пользователя
     *
     * @return string
     */
    final public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Сериализация данных
     * @return mixed|void
     */
    public function jsonSerializes():array
    {
        return [
            'id' => $this->id,
            'title' => $this->full_name,
            'year' => $this->phone,
        ];
    }

    /**
     * Создает сущность из массива
     * @param array $data
     * @return AbstractUser
     */
    abstract public static function createFromArray(array $data): AbstractUser;
}