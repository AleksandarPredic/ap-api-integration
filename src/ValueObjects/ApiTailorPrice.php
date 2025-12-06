<?php

namespace ApApi\ValueObjects;

use ApApi\Traits\PriceFormattingTrait;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Value object representing a tailor price item
 */
class ApiTailorPrice
{
    use PriceFormattingTrait;

    /**
     * Constructor for ApiTailorPrice
     *
     * @param string $name The name of the tailor service
     * @param float $price The price of the tailor service
     */
    public function __construct(
        private readonly string $name,
        private readonly float $price
    ) {
    }

    /**
     * Get the service name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the service price
     *
     * @return string
     */
    public function getPrice(): string
    {
        return $this->formatPrice($this->price);
    }
}
