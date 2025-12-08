<?php

namespace ApApi\ValueObjects;

// Do not allow directly accessing this file.

if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Value object representing a complete store with all its data
 *
 * This class uses type-safe collections for tailor prices and department prices.
 * Collections validate object types during construction and may throw InvalidArgumentException.
 *
 * @see ApiTailorPricesCollection
 * @see ApiPricesPerDepartmentCollection
 */
class ApiStore
{
    /**
     * Constructor for ApiStore
     *
     * @param string $storeName The store name
     * @param int $branchId The unique branch identifier
     * @param string $branchNo The branch number/code
     * @param string $streetAddress The street address
     * @param string $city The city
     * @param string $state The state
     * @param string $zip The ZIP code
     * @param string $phone The phone number
     * @param string $email The email address
     * @param ApiStoreHours $storeHours The store hours
     * @param ApiTailorPricesCollection $tailorPrices Collection of tailor prices
     * @param ApiPricesPerDepartmentCollection $pricesPerDepartments Collection of prices per departments
     * @throws \InvalidArgumentException When collections are constructed with invalid object types
     */
    public function __construct(
        private readonly string $storeName,
        private readonly int $branchId,
        private readonly string $branchNo,
        private readonly string $streetAddress,
        private readonly string $city,
        private readonly string $state,
        private readonly string $zip,
        private readonly string $phone,
        private readonly string $email,
        private readonly ApiStoreHours $storeHours,
        private readonly ApiTailorPricesCollection $tailorPrices,
        private readonly ApiPricesPerDepartmentCollection $pricesPerDepartments
    ) {
    }

    /**
     * Get the store name
     *
     * @return string
     */
    public function getStoreName(): string
    {
        return $this->storeName;
    }

    /**
     * Get the branch ID
     *
     * @return int
     */
    public function getBranchId(): int
    {
        return $this->branchId;
    }

    /**
     * Get the branch number
     *
     * @return string
     */
    public function getBranchNo(): string
    {
        return $this->branchNo;
    }

    /**
     * Get the street address
     *
     * @return string
     */
    public function getStreetAddress(): string
    {
        return $this->streetAddress;
    }

    /**
     * Get the city
     *
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * Get the state
     *
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Get the ZIP code
     *
     * @return string
     */
    public function getZip(): string
    {
        return $this->zip;
    }

    /**
     * Get the phone number
     *
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Get the email address
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get store hours
     *
     * @return ApiStoreHours
     */
    public function getStoreHours(): ApiStoreHours
    {
        return $this->storeHours;
    }

    /**
     * Get tailor prices
     *
     * @return ApiTailorPricesCollection
     */
    public function getTailerPrices(): ApiTailorPricesCollection
    {
        return $this->tailorPrices;
    }

    /**
     * Get prices per departments
     *
     * @return ApiPricesPerDepartmentCollection
     */
    public function getPricesPerDepartments(): ApiPricesPerDepartmentCollection
    {
        return $this->pricesPerDepartments;
    }
}
