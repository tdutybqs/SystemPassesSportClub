<?php

require_once __DIR__ . "/AbstractUser.php";

/**
 * Клиент
 */
class Customer extends AbstractUser
{
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
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * Установить пол клиента
     * @param string $sex
     * @return Customer
     */
    public function setSex(string $sex): Customer
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Получить дату рождения клиента
     * @return string
     */
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    /**
     * Установить дату рождения клиента
     * @param string $birthdate
     * @return Customer
     */
    public function setBirthdate(string $birthdate): Customer
    {
        $this->birthdate = $birthdate;
        return $this;
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
     * Установить паспортные данные клиента
     * @param string $passport
     * @return Customer
     */
    public function setPassport(string $passport): Customer
    {
        $this->passport = $passport;
        return $this;
    }

    public function jsonSerialize():array
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
}