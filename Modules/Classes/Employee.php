<?php

require_once __DIR__ . '/AbstractUser.php';
require_once __DIR__."/Exceptions/InvalidFilePath.php";
require_once __DIR__."/Exceptions/InvalidDataStructureException.php";

//TODO заброшенный класс. Он нигде не используется. Работа над ним не ведется
/**
 * Сотрудники
 */
final class Employee extends AbstractUser
{
    /**
     * Конструктор сотрудник
     * @inheritDoc
     * @param string $position - позиция
     * @param int $salary - зарплата
     */
    public function __construct(int $id, string $full_name, string $phone, string $position, int $salary)
    {
        parent::__construct($id, $full_name, $phone);
        $this->position = $position;
        $this->salary = $salary;
    }

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
    final public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Получить зарплату сотрудника
     * @return int
     */
    final public function getSalary(): int
    {
        return $this->salary;
    }

    /**
     * Сериализация объекта
     * @return mixed|void
     */
    public function jsonSerialize():array
    {
    }
}