<?php

namespace EfTech\SportClub\Entity;

use Exception;
use EfTech\SportClub\Infrastructure\InvalidDataStructureException;

require_once __DIR__ . '/AbstractUser.php';
require_once __DIR__ . "/../Infrastructure/InvalidDataStructureException.php";

//TODO заброшенный класс. Он нигде не используется. Работа над ним не ведется
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
     * Конструктор сотрудник
     * @inheritDoc
     * @param string $position - позиция
     * @param int $salary - зарплата
     */
    public function __construct(int $id, string $fullName, string $phone, string $position, int $salary)
    {
        parent::__construct($id, $fullName, $phone);
        $this->position = $position;
        $this->salary = $salary;
    }

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

    public static function createFromArray(array $data): Employee
    {
        $requiredFields = [
            'customer_id',
            'full_name',
            'phone',
            'position',
            'salary'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new static($data['customer_id'], $data['full_name'], $data['phone'], $data['position'],
            $data['salary']);
    }
}