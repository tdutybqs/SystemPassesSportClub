<?php

require_once __DIR__ . '/Customer.php';

/**
 * Программы
 */
class Programme
{
    /**
     * id программы
     * @var int
     */
    public int $id;

    /**
     * название программы
     * @var string
     */
    public string $name;

    /**
     * срок работы программы
     * @var string
     */
    public string $duration;

    /**
     * подготовка программы
     * @var string
     */
    public string $discount;

    /**
     * клиент программы
     * @var Customer
     */
    public Customer $customer;
}