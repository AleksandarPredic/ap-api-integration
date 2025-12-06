<?php

namespace ApApi\DataSync\Transformers;

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class ApiStoreTransformer
 * Transforms raw API store data into a clean array format keyed by branchId
 */
class ApiStoreTransformer
{
    /**
     * Transform array of stores data and key by branchId
     *
     * @param array $storesData Raw stores data from API
     * @return array Transformed data keyed by branchId
     */
    public function transform(array $storesData): array
    {
        $transformedStores = [];

        foreach ($storesData as $storeData) {
            if (!isset($storeData['branchId'])) {
                continue; // Skip stores without branchId
            }

            $branchId = $storeData['branchId'];
            $transformedStores[$branchId] = $this->transformSingleStore($storeData);
        }

        return $transformedStores;
    }

    /**
     * Transform a single store data structure
     *
     * @param array $storeData Single store data
     * @return array Transformed store data
     */
    private function transformSingleStore(array $storeData): array
    {
        return [
            'id' => $storeData['branchId'] ?? null,
            'branch_no' => $storeData['branchNo'] ?? '',
            'location' => $storeData['storeLocation'] ?? '',
            'address' => [
                'street' => $storeData['streetAddress'] ?? '',
                'city' => $storeData['city'] ?? '',
                'state' => $storeData['state'] ?? '',
                'zip' => $storeData['zip'] ?? ''
            ],
            'contact' => [
                'phone' => $storeData['phone'] ?? '',
                'email' => $storeData['email'] ?? ''
            ],
            'hours' => $this->transformStoreHours($storeData['storeHours'] ?? []),
            'services' => [
                'tailoring' => $this->transformTailorPrices($storeData['tailerPrices'] ?? []),
                'departments' => $this->transformDepartments($storeData['departments'] ?? [])
            ]
        ];
    }

    /**
     * Transform store hours data
     *
     * @param array $hoursData Store hours data
     * @return array Transformed hours data
     */
    private function transformStoreHours(array $hoursData): array
    {
        return [
            'monday_friday' => $hoursData['monFriHours'] ?? '',
            'saturday' => $hoursData['saturdayHours'] ?? '',
            'sunday' => $hoursData['sundayHours'] ?? ''
        ];
    }

    /**
     * Transform tailor prices data
     *
     * @param array $tailorPrices Tailor prices data
     * @return array Transformed tailor prices
     */
    private function transformTailorPrices(array $tailorPrices): array
    {
        $transformed = [];

        foreach ($tailorPrices as $service) {
            if (!isset($service['name'])) {
                continue;
            }

            $transformed[] = [
                'name' => $service['name'],
                'price' => $service['price'] ?? 0
            ];
        }

        return $transformed;
    }

    /**
     * Transform departments data
     *
     * @param array $departments Departments data
     * @return array Transformed departments data
     */
    private function transformDepartments(array $departments): array
    {
        $transformed = [];

        foreach ($departments as $department) {
            if (!isset($department['deptName'])) {
                continue;
            }

            $transformed[$department['deptName']] = [
                'name' => $department['deptName'],
                'items' => $this->transformDepartmentItems($department['items'] ?? [])
            ];
        }

        return $transformed;
    }

    /**
     * Transform department items
     *
     * @param array $items Department items
     * @return array Transformed items
     */
    private function transformDepartmentItems(array $items): array
    {
        $transformed = [];

        foreach ($items as $item) {
            if (!isset($item['name'])) {
                continue;
            }

            $transformed[] = [
                'name' => $item['name'],
                'price' => $item['price'] ?? 0
            ];
        }

        return $transformed;
    }
}
