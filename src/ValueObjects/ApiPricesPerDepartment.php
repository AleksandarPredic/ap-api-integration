<?php

namespace ApApi\ValueObjects;

// Do not allow directly accessing this file.

if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Value object representing a department with its items
 */
class ApiPricesPerDepartment
{
    /**
     * Constructor for ApiDepartment
     *
     * @param string $department The department name
     * @param ApiDepartmentItemPrices[] $items Array of department items
     */
    public function __construct(
        private readonly string $department,
        private readonly array $items
    ) {
    }

    /**
     * Get the department name
     *
     * @return string
     */
    public function getDepartmentName(): string
    {
        return $this->department;
    }

    /**
     * Get the department items
     *
     * @return ApiDepartmentItemPrices[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
