<?php

namespace ApApi\ValueObjects;

use ApApi\Traits\PriceFormattingTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Value object representing a department item (store service/product)
 */
class ApiDepartmentItemPrices
{
    use PriceFormattingTrait;

    /**
     * Constructor for ApiDepartmentItem
     *
     * @param string $name The name of the department item
     * @param float $price The price of the department item
     */
    public function __construct(
        private readonly string $name,
        private readonly float $price
    ) {
    }

    /**
     * Get the item name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the item price
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->formatPrice($this->price);
    }
}
