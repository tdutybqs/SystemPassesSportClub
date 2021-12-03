<?php

/**
 * Клиент
 */
class Customer
{
    /**
     * id клиента
     * @var int
     */
    public int $id;

    /**
     * ФИО клиента
     * @var string
     */
    public string $full_name;

    /**
     * пол клиента
     * @var string
     */
    public string $sex;

    /**
     * д.р. клиента
     * @var string
     */
    public string $birthdate;

    /**
     * телефон клиента
     * @var string
     */
    public string $phone;

    /**
     * паспорт клиента
     * @var string
     */
    public string $passport;
}