<?php

namespace ApApi\ValueObjects;

use Iterator;
use Countable;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Collection class for ApiTailorPrice objects
 *
 * This collection enforces type safety by validating that all items are instances
 * of ApiTailorPrice. It provides iteration capabilities and collection-specific methods.
 */
class ApiTailorPricesCollection implements Iterator, Countable
{
    private array $items = [];
    private int $position = 0;

    /**
     * Constructor for ApiTailorPricesCollection
     *
     * @param ApiTailorPrice[] $tailorPrices Array of ApiTailorPrice objects
     * @throws \InvalidArgumentException When any item is not an instance of ApiTailorPrice
     */
    public function __construct(array $tailorPrices)
    {
        foreach ($tailorPrices as $tailorPrice) {
            if (!$tailorPrice instanceof ApiTailorPrice) {
                throw new \InvalidArgumentException('All items must be instances of ApiTailorPrice');
            }
        }
        $this->items = array_values($tailorPrices);
    }

    /**
     * Get all tailor prices as array
     *
     * @return ApiTailorPrice[]
     */
    public function getAll(): array
    {
        return $this->items;
    }

    /**
     * Check if collection is empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Get count of items
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Add a tailor price to the collection
     *
     * @param ApiTailorPrice $tailorPrice
     * @return void
     * @throws \InvalidArgumentException When the provided object is not an ApiTailorPrice instance
     */
    public function add(ApiTailorPrice $tailorPrice): void
    {
        if (!$tailorPrice instanceof ApiTailorPrice) {
            throw new \InvalidArgumentException('Item must be an instance of ApiTailorPrice');
        }
        $this->items[] = $tailorPrice;
    }

    // Iterator interface implementation
    public function current(): ?ApiTailorPrice
    {
        return $this->items[$this->position] ?? null;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }
}
