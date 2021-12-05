<?php

require_once __DIR__."/Pass.php";

/**
 * Льготы
 */
class BenefitPass extends Pass
{
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
    public function getTypeBenefit(): string
    {
        return $this->type_benefit;
    }

    /**
     * Установить тип льготы
     * @param string $type_benefit
     * @return BenefitPass
     */
    public function setTypeBenefit(string $type_benefit): BenefitPass
    {
        $this->type_benefit = $type_benefit;
        return $this;
    }

    /**
     * Получить номер документа
     * @return string
     */
    public function getNumberDocument(): string
    {
        return $this->number_document;
    }

    /**
     * Установить номер документа
     * @param int $number_document
     * @return BenefitPass
     */
    public function setNumberDocument(int $number_document): BenefitPass
    {
        $this->number_document = $number_document;
        return $this;
    }

    /**
     * Получить дату окончания действия льготы
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Установить дату окончания действия льготы
     * @param string $end
     * @return BenefitPass
     */
    public function setEnd(string $end): BenefitPass
    {
        $this->end = $end;
        return $this;
    }

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
}