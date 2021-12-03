<?php

require_once __DIR__ . '/Customer.php';

/**
 * Абонементы
 */
class Pass
{
    /**
     * id абонемента
     * @var int
     */
    public int $id;

    /**
     * срок работы абонемента
     * @var string
     */
    public string $duration;

    /**
     * скидка абонемента
     * @var string
     */
    public string $discount;

    /**
     * клиент абонемента
     * @var Customer
     */
    public Customer $customer;
}