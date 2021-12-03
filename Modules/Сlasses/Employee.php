<?php

require_once __DIR__ . '/Customer.php';

/**
 * Сотрудники
 */
class Employee
{
    /**
     * id клиента, являющегося данным сотрудником?
     * @var Customer
     */
    public Customer $customer;

    /**
     * ФИО сотрудника
     * @var string
     */
    public string $full_name;

    /**
     * телефон сотрудника
     * @var string
     */
    public string $phone;

    /**
     * должность сотрудника
     * @var string
     */
    public string $position;

    /**
     * зарплата сотрудника
     * @var int
     */
    public int $salary;
}