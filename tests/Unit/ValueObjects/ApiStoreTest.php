<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

// Define WordPress constant to avoid "Direct script access denied" error
if (!defined('ABSPATH')) {
    define('ABSPATH', '/fake/wordpress/path/');
}


use ApApi\ValueObjects\ApiPricesPerDepartmentCollection;
use ApApi\ValueObjects\ApiStore;
use ApApi\ValueObjects\ApiStoreHours;
use ApApi\ValueObjects\ApiTailorPricesCollection;
use PHPUnit\Framework\TestCase;

class ApiStoreTest extends TestCase
{
    private function createTestStoreHours(): ApiStoreHours
    {
        return new ApiStoreHours(
            '9:00 AM - 8:00 PM',
            '10:00 AM - 6:00 PM',
            'Closed'
        );
    }

    private function createTestApiStore(): ApiStore
    {
        return new ApiStore(
            storeName: 'Test Store',
            branchId: 123,
            branchNo: 'BR001',
            streetAddress: '123 Main St',
            city: 'Anytown',
            state: 'CA',
            zip: '12345',
            phone: '555-0123',
            email: 'test@store.com',
            storeHours: $this->createTestStoreHours(),
            tailorPrices: new ApiTailorPricesCollection([]),
            pricesPerDepartments: new ApiPricesPerDepartmentCollection([])
        );
    }

    /*
     * Test constructor and basic getters
     */
    public function testConstructorAndBasicGetters(): void
    {
        // Arrange (setup test data)
        $storeHours = $this->createTestStoreHours();

        // Act (create the object)
        $store = new ApiStore(
            storeName: 'My Store',
            branchId: 456,
            branchNo: 'BR002',
            streetAddress: '456 Oak Ave',
            city: 'Springfield',
            state: 'IL',
            zip: '62701',
            phone: '555-9876',
            email: 'contact@mystore.com',
            storeHours: $storeHours,
            tailorPrices: new ApiTailorPricesCollection([]),
            pricesPerDepartments: new ApiPricesPerDepartmentCollection([])
        );

        // Assert (verify the results)
        $this->assertEquals('My Store', $store->getStoreName());
        $this->assertEquals(456, $store->getBranchId());
        $this->assertEquals('BR002', $store->getBranchNo());
        $this->assertEquals('456 Oak Ave', $store->getStreetAddress());
        $this->assertEquals('Springfield', $store->getCity());
        $this->assertEquals('IL', $store->getState());
        $this->assertEquals('62701', $store->getZip());
        $this->assertEquals('555-9876', $store->getPhone());
        $this->assertEquals('contact@mystore.com', $store->getEmail());
        $this->assertSame($storeHours, $store->getStoreHours());
    }

    public function testArrayProperties(): void
    {
        // Test with empty arrays
        $store = $this->createTestApiStore();

        $this->assertInstanceOf(ApiTailorPricesCollection::class, $store->getTailerPrices());
        $this->assertEmpty($store->getTailerPrices());

        $this->assertInstanceOf(ApiPricesPerDepartmentCollection::class, $store->getPricesPerDepartments());
        $this->assertEmpty($store->getPricesPerDepartments());
    }

    public function testWithSpecialCharacters(): void
    {
        $storeHours = $this->createTestStoreHours();

        $store = new ApiStore(
            storeName: "Bob's Store & More",
            branchId: 0,
            branchNo: '',
            streetAddress: '123 Main St, Apt #4',
            city: 'São Paulo',
            state: 'SP',
            zip: '01234-567',
            phone: '+1-555-123-4567',
            email: 'test+tag@domain.co.uk',
            storeHours: $storeHours,
            tailorPrices: new ApiTailorPricesCollection([]),
            pricesPerDepartments: new ApiPricesPerDepartmentCollection([])
        );

        $this->assertEquals("Bob's Store & More", $store->getStoreName());
        $this->assertEquals('São Paulo', $store->getCity());
        $this->assertEquals(0, $store->getBranchId());
        $this->assertEquals('', $store->getBranchNo());
    }

    public function testWithCollections(): void
    {
        // Test that collections are properly handled and arrays are returned by getter methods
        $storeHours = $this->createTestStoreHours();
        $tailorPricesCollection = new ApiTailorPricesCollection([]);
        $pricesPerDepartmentsCollection = new ApiPricesPerDepartmentCollection([]);

        $store = new ApiStore(
            storeName: 'Collection Test Store',
            branchId: 123,
            branchNo: 'CT001',
            streetAddress: '123 Test St',
            city: 'Test City',
            state: 'TC',
            zip: '12345',
            phone: '555-1234',
            email: 'test@collection.com',
            storeHours: $storeHours,
            tailorPrices: $tailorPricesCollection,
            pricesPerDepartments: $pricesPerDepartmentsCollection
        );

        // Test that collection getter methods return collection instances
        $this->assertInstanceOf(ApiTailorPricesCollection::class, $store->getTailerPrices());
        $this->assertInstanceOf(ApiPricesPerDepartmentCollection::class, $store->getPricesPerDepartments());
        $this->assertEmpty($store->getTailerPrices());
        $this->assertEmpty($store->getPricesPerDepartments());

        // Test collection methods
        $this->assertTrue($store->getTailerPrices()->isEmpty());
        $this->assertTrue($store->getPricesPerDepartments()->isEmpty());
        $this->assertEquals(0, $store->getTailerPrices()->count());
        $this->assertEquals(0, $store->getPricesPerDepartments()->count());
    }

    public function testEdgeCaseValues(): void
    {
        $storeHours = $this->createTestStoreHours();

        $store = new ApiStore(
            storeName: '',  // Empty string
            branchId: PHP_INT_MAX,  // Maximum integer
            branchNo: '0',  // Zero as string
            streetAddress: str_repeat('A', 255),  // Very long address
            city: 'X',  // Single character
            state: 'CA',
            zip: '00000',  // All zeros
            phone: '',  // Empty phone
            email: 'a@b.c',  // Minimal valid email format
            storeHours: $storeHours,
            tailorPrices: new ApiTailorPricesCollection([]),
            pricesPerDepartments: new ApiPricesPerDepartmentCollection([])
        );

        $this->assertEquals('', $store->getStoreName());
        $this->assertEquals(PHP_INT_MAX, $store->getBranchId());
        $this->assertEquals('0', $store->getBranchNo());
        $this->assertEquals(str_repeat('A', 255), $store->getStreetAddress());
        $this->assertEquals('X', $store->getCity());
    }
}

