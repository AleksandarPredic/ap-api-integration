<?php

namespace ApApi\Traits;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Trait PriceFormattingTrait
 * Provides consistent price formatting functionality across the plugin
 */
trait PriceFormattingTrait
{
    /**
     * Format a price value with 2 decimal places
     *
     * @param float $price The price to format
     * @return string The formatted price with 2 decimal places
     */
    protected function formatPrice(float $price): string
    {
        return number_format($price, 2);
    }
}
