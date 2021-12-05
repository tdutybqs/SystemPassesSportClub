<?php

require_once __DIR__ . '/AbstractUser.php';

/**
 * Сотрудники
 */
class Employee extends AbstractUser
{
    /**
     * Должность сотрудника
     * @var string
     */
    private string $position;

    /**
     * Зарплата сотрудника
     * @var int
     */
    private int $salary;

    /**
     * Получить позицию сотрудника
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Установить позицию сотрудника
     * @param string $position
     * @return Employee
     */
    public function setPosition(string $position): Employee
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Получить зарплату сотрудника
     * @return int
     */
    public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * Установить зарплату сотруднику
     * @param int $salary
     * @return Employee
     */
    public function setSalary(int $salary): Employee
    {
        $this->salary = $salary;
        return $this;
    }

    public function jsonSerialize()
    {
    }
}