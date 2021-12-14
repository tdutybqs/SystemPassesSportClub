<?php

namespace EfTech\SportClub\Entity;
require_once __DIR__ . "/Customer.php";

use EfTech\SportClub\Infrastructure\InvalidDataStructureException;

final class CustomerView extends Customer
{
    public function jsonSerialize(): array
    {
        return [
            "customer_id" => $this->getId(),
            "full_name" => $this->getFullName(),
            "sex" => $this->getSex(),
            "birthdate" => $this->getBirthdate(),
            "phone" => $this->getPhone(),
            "passport" => $this->getPassport(),
            'purchased_items' => $this->getPurchasedItems()
        ];
    }
}