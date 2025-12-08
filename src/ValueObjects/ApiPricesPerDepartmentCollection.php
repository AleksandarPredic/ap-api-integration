<?php

namespace ApApi\ValueObjects;

use Iterator;
use Countable;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Collection class for ApiPricesPerDepartment objects
 *
 * This collection enforces type safety by validating that all items are instances
 * of ApiPricesPerDepartment. It provides iteration capabilities and collection-specific methods.
 */
class ApiPricesPerDepartmentCollection implements Iterator, Countable
{
    private array $items = [];
    private int $position = 0;

    /**
     * Constructor for ApiPricesPerDepartmentCollection
     *
     * @param ApiPricesPerDepartment[] $pricesPerDepartments Array of ApiPricesPerDepartment objects
     * @throws \InvalidArgumentException When any item is not an instance of ApiPricesPerDepartment
     */
    public function __construct(array $pricesPerDepartments)
    {
        foreach ($pricesPerDepartments as $pricesPerDepartment) {
            if (!$pricesPerDepartment instanceof ApiPricesPerDepartment) {
                throw new \InvalidArgumentException('All items must be instances of ApiPricesPerDepartment');
            }
        }
        $this->items = array_values($pricesPerDepartments);
    }

    /**
     * Get all prices per departments as array
     *
     * @return ApiPricesPerDepartment[]
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
     * Add department prices to the collection
     *
     * @param ApiPricesPerDepartment $pricesPerDepartment
     * @return void
     * @throws \InvalidArgumentException When the provided object is not an ApiPricesPerDepartment instance
     */
    public function add(ApiPricesPerDepartment $pricesPerDepartment): void
    {
        if (!$pricesPerDepartment instanceof ApiPricesPerDepartment) {
            throw new \InvalidArgumentException('Item must be an instance of ApiPricesPerDepartment');
        }
        $this->items[] = $pricesPerDepartment;
    }

    // Iterator interface implementation
    public function current(): ?ApiPricesPerDepartment
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
