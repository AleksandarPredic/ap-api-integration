<?php

namespace ApApi\Repositories;

use ApApi\DataSync\Traits\ApiDataStorageTrait;
use ApApi\ValueObjects\ApiDepartmentItemPrices;
use ApApi\ValueObjects\ApiPricesPerDepartment;
use ApApi\ValueObjects\ApiStore;
use ApApi\ValueObjects\ApiStoreHours;
use ApApi\ValueObjects\ApiTailorPrice;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Repository class for handling store data operations
 *
 * Retrieves and processes store data from WordPress options,
 * converting transformed data to ApiStore value objects.
 *
 * @package ApApi\DataSync\Repositories
 */
class ApiStoreRepository
{
    use ApiDataStorageTrait;

    /**
     * Transient key for caching transformed API data
     */
    private const string CACHE_KEY = 'ap_api_stores_repository_cache';

    /**
     * Cache expiration time (12 hours)
     */
    private const int CACHE_EXPIRATION = 12 * HOUR_IN_SECONDS;

    /**
     * Find a single store by branch ID using cached transformed data
     *
     * @param int $branchId The branch ID to search for
     * @return ApiStore|null The store if found, null otherwise
     */
    public function findStoreByBranchId(int $branchId): ?ApiStore
    {
        $transformedData = $this->getCachedTransformedData();

        if (!is_array($transformedData) || empty($transformedData)) {
            return null;
        }

        // Direct access by branchId key
        if (!isset($transformedData[$branchId])) {
            return null;
        }

        return $this->createStoreFromTransformedArray($transformedData[$branchId]);
    }

    /**
     * Find multiple stores by an array of branch IDs using cached transformed data
     *
     * @param int[] $branchIds Array of branch IDs to search for
     * @return ApiStore[] Array of found stores (may be fewer than requested if some not found)
     */
    public function findStoresByBranchIds(array $branchIds): array
    {
        if (empty($branchIds)) {
            return [];
        }

        $transformedData = $this->getCachedTransformedData();

        if (!is_array($transformedData)) {
            return [];
        }

        $foundStores = [];

        foreach ($branchIds as $branchId) {
            if (isset($transformedData[$branchId])) {
                $foundStores[] = $this->createStoreFromTransformedArray($transformedData[$branchId]);
            }
        }

        return $foundStores;
    }

    /**
     * Get all stores as value objects using cached data
     *
     * @return ApiStore[] Array of all stores
     */
    public function getAllStores(): array
    {
        $transformedData = $this->getCachedTransformedData();

        if (!is_array($transformedData)) {
            return [];
        }

        $stores = [];
        foreach ($transformedData as $storeData) {
            $stores[] = $this->createStoreFromTransformedArray($storeData);
        }

        return $stores;
    }

    /**
     * Clear the repository cache
     * Useful for forcing fresh data fetch or debugging
     *
     * @return bool True on success, false on failure
     */
    public function clearCache(): bool
    {
        return delete_transient(self::CACHE_KEY);
    }

    /**
     * Get cached transformed data with fallback to database
     *
     * @return array|false The cached data or false if not found
     */
    private function getCachedTransformedData(): mixed
    {
        // Try to get from transient cache first
        $cachedData = get_transient(self::CACHE_KEY);

        if (! empty($cachedData)) {
            return $cachedData;
        }

        // If not in cache, get from database via trait
        $data = $this->getTransformedApiData();

        if ($data !== false && is_array($data)) {
            // Store in transient for future requests
            set_transient(self::CACHE_KEY, $data, self::CACHE_EXPIRATION);
        }

        return $data;
    }

    /**
     * Create ApiStore instance from transformed array data
     *
     * @param array $data Transformed store data
     * @return ApiStore
     */
    private function createStoreFromTransformedArray(array $data): ApiStore
    {
        // Process store hours from transformed structure
        $storeHours = $this->createStoreHoursFromTransformedArray($data['hours'] ?? []);

        // Process tailor prices from transformed structure
        $tailerPrices = [];
        $tailoringData = $data['services']['tailoring'] ?? [];
        foreach ($tailoringData as $priceData) {
            if (is_array($priceData)) {
                $tailerPrices[] = $this->createTailorPriceFromTransformedArray($priceData);
            }
        }

        // Process departments from transformed structure
        $departments = [];
        $departmentsData = $data['services']['departments'] ?? [];
        foreach ($departmentsData as $deptData) {
            if (is_array($deptData)) {
                $departments[] = $this->createDepartmentFromTransformedArray($deptData);
            }
        }

        return new ApiStore(
            storeName: (string) ($data['location'] ?? ''),
            branchId: (int) ($data['id'] ?? 0),
            branchNo: (string) ($data['branch_no'] ?? ''),
            streetAddress: (string) ($data['address']['street'] ?? ''),
            city: (string) ($data['address']['city'] ?? ''),
            state: (string) ($data['address']['state'] ?? ''),
            zip: (string) ($data['address']['zip'] ?? ''),
            phone: (string) ($data['contact']['phone'] ?? ''),
            email: (string) ($data['contact']['email'] ?? ''),
            storeHours: $storeHours,
            tailorPrices: $tailerPrices,
            pricesPerDepartments: $departments
        );
    }

    /**
     * Create ApiStoreHours instance from transformed array data
     *
     * @param array $data Transformed store hours data
     * @return ApiStoreHours
     */
    private function createStoreHoursFromTransformedArray(array $data): ApiStoreHours
    {
        return new ApiStoreHours(
            monFriHours: (string) ($data['monday_friday'] ?? ''),
            saturdayHours: (string) ($data['saturday'] ?? ''),
            sundayHours: (string) ($data['sunday'] ?? '')
        );
    }

    /**
     * Create ApiTailorPrice instance from transformed array data
     *
     * @param array $data Transformed tailor price data
     * @return ApiTailorPrice
     */
    private function createTailorPriceFromTransformedArray(array $data): ApiTailorPrice
    {
        return new ApiTailorPrice(
            name: (string) ($data['name'] ?? ''),
            price: (float) ($data['price'] ?? 0.0)
        );
    }

    /**
     * Create ApiDepartment instance from transformed array data
     *
     * @param array $data Transformed department data
     * @return ApiPricesPerDepartment
     */
    private function createDepartmentFromTransformedArray(array $data): ApiPricesPerDepartment
    {
        $items = [];
        $itemsData = $data['items'] ?? [];

        foreach ($itemsData as $itemData) {
            if (is_array($itemData)) {
                $items[] = $this->createDepartmentItemFromTransformedArray($itemData);
            }
        }

        return new ApiPricesPerDepartment(
            department: (string) ($data['name'] ?? ''),
            items: $items
        );
    }

    /**
     * Create ApiDepartmentItem instance from transformed array data
     *
     * @param array $data Transformed department item data
     * @return ApiDepartmentItemPrices
     */
    private function createDepartmentItemFromTransformedArray(array $data): ApiDepartmentItemPrices
    {
        return new ApiDepartmentItemPrices(
            name: (string) ($data['name'] ?? ''),
            price: (float) ($data['price'] ?? 0.0)
        );
    }
}
