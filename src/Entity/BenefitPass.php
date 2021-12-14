<?php

namespace EfTech\SportClub\Entity;
require_once __DIR__ . "/Pass.php";
require_once __DIR__."/../Infrastructure/InvalidDataStructureException.php";

use Exception;
use EfTech\SportClub\Infrastructure\InvalidDataStructureException;

/**
 * Льготы
 */
class BenefitPass extends Pass
{
    /**
     * Конструктор льготного абонемента
     * @inheritDoc
     * @param $type_benefit
     * @param $number_document
     * @param $end
     */
    public function __construct(
        int $id,
        string $duration,
        string $discount,
        Customer $customer,
        $type_benefit,
        $number_document,
        $end
    ) {
        parent::__construct($id, $duration, $discount, $customer);
        $this->type_benefit = $type_benefit;
        $this->number_document = $number_document;
        $this->end = $end;
    }

    /**
     * тип льготы
     * @var string
     */
    private string $type_benefit;

    /**
     * номер льготы
     * @var string
     */
    private string $number_document;

    /**
     * конец срока продления льготы
     * @var string
     */
    private string $end;

    /**
     * Получить тип льготы
     * @return string
     */
    final public function getTypeBenefit(): string
    {
        return $this->type_benefit;
    }

    /**
     * Получить номер документа
     * @return string
     */
    final public function getNumberDocument(): string
    {
        return $this->number_document;
    }

    /**
     * Получить дату окончания действия льготы
     * @return string
     */
    final public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Реализация функции jsonSerialize
     * @return array
     */
    public function jsonSerialize(): array
    {
        $jsonData = $this->getCustomer()->jsonSerialize();
        $jsonData['benefit'] = [
            "type_benefit" => $this->getTypeBenefit(),
            "number_document" => $this->getNumberDocument(),
            "end" => $this->getEnd()
        ];
        return $jsonData;
    }

    /**
     * Создание объекта из массива
     * @param array $data
     * @return Pass
     * @throws Exception - некорректная структура файла
     */
    public static function createFromArray(array $data): Pass
    {
        $requiredFields = [
            'end',
            'number_document',
            'type_benefit',
            'pass_id',
            'duration',
            'discount',
            'customer'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0){
            $errMsg = sprintf('Отсутствуют обязательные элементы: %s', implode(',', $missingFields));
            throw new InvalidDataStructureException($errMsg);
        }

        return new static($data['pass_id'], $data['duration'], $data['discount'], $data['customer'],
            $data['type_benefit'], $data['number_document'], $data['end']);
    }
}