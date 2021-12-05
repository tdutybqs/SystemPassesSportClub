<?php


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
     * Получить id пользователя
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Установить id пользователя
     *
     * @param int $id
     * @return AbstractUser
     */
    public function setId(int $id): AbstractUser
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Получить ФИО пользователя
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * Установить ФИО пользователя
     *
     * @param string $full_name
     * @return AbstractUser
     */
    public function setFullName(string $full_name): AbstractUser
    {
        $this->full_name = $full_name;
        return $this;
    }

    /**
     * Получить телефон пользователя
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Установить телефон пользователя
     *
     * @param string $phone
     * @return AbstractUser
     */
    public function setPhone(string $phone): AbstractUser
    {
        $this->phone = $phone;
        return $this;
    }

    abstract public function jsonSerialize();

}