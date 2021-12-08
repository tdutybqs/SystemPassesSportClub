<?php

require_once __DIR__."/Exceptions/InvalidFilePath.php";
require_once __DIR__."/Exceptions/InvalidDataStructureException.php";

/**
 * Программы
 */
class Programme implements JsonSerializable
{

    /**
     * id программы
     * @var int
     */
    private int $id;

    /**
     * название программы
     * @var string
     */
    private string $name;

    /**
     * срок работы программы
     * @var string
     */
    private string $duration;

    /**
     * Уровень сложности программы
     * @var string
     */
    private string $discount;

    /**
     * Конструктор программы
     * @param int $id - идентификатор программы
     * @param string $name - наименование пораммы
     * @param string $duration - период действия программы
     * @param string $discount - уровень сложности программы
     */
    public function __construct(int $id, string $name, string $duration, string $discount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->duration = $duration;
        $this->discount = $discount;
    }

    /**
     * Получить id
     * @return int
     */
    final public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить название программы
     * @return string
     */
    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получить срок действия программы
     * @return string
     */
    final public function getDuration(): string
    {
        return $this->duration;
    }

    /**
     * Получить скидку программы
     * @return string
     */
    final public function getDiscount(): string
    {
        return $this->discount;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id_programme' => $this->getId(),
            'name' => $this->getName(),
            'duration' => $this->getDuration(),
            'discount' => $this->getDiscount()
        ];
    }

    /**
     * Создание объекта из массива
     * @param array $data
     * @return Programme
     * @throws InvalidDataStructureException - некорректная структура файла
     */
    public static function createFromArray(array $data): Programme
    {
        $requiredFields = [
            'id_programme',
            'name',
            'duration',
            'discount'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new static($data['id_programme'], $data['name'], $data['duration'], $data['discount']);
    }
}