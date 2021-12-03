<?php

//require_once __DIR__ . '/Customer.php';
require_once __DIR__ . '/Pass.php';
require_once __DIR__ . '/BenefitPass.php';
require_once __DIR__ . '/Programme.php';

/**
 * Купленные продукта
 */
class PurchasedItem
{
    /**
     * id продукта
     * @var int
     */
    public int $purchased_item_id;

    /**
     * id абонемента в продкукте
     * @var Pass
     */
    public Pass $pass;

//    /**
//     * id льготы в продкукте
//     * @var BenefitPass
//     */
//    public BenefitPass $benefit_pass;

    /**
     * id программы в продкукте
     * @var Programme
     */
    public Programme $programme;

    /**
     * цена продукта
     * @var int
     */
    public int $price;
}