<?php

require_once __DIR__ . '/Customer.php';

/**
 * Льготы
 * наследуются от Абонементов
 */
class BenefitPass extends Pass
{
    /**
     * тип льготы
     * @var string
     */
    public string $type_benefit;

    /**
     * номер льготы
     * @var int
     */
    public int $number_document;

    /**
     * конец срока продления льготы
     * @var string
     */
    public string $end;
}