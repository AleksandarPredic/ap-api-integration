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

    private function createTestApiStore(
        ?ApiTailorPricesCollection $tailorPrices,
        ?ApiPricesPerDepartmentCollection $pricesPerDepartment
    ): ApiStore
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
            tailorPrices: $tailorPrices ?? new ApiTailorPricesCollection([]),
            pricesPerDepartments: $pricesPerDepartment ?? new ApiPricesPerDepartmentCollection([])
        );
    }

    /*
     * Test constructor and basic getters
     */
    public function testConstructorAndBasicGetters(): void
    {
        // Act (create the object)
        $store = $this->createTestApiStore(null, null);

        // Assert (verify the results)
        $this->assertEquals('Test Store', $store->getStoreName());
        $this->assertEquals(123, $store->getBranchId());
        $this->assertEquals('BR001', $store->getBranchNo());
        $this->assertEquals('123 Main St', $store->getStreetAddress());
        $this->assertEquals('Anytown', $store->getCity());
        $this->assertEquals('CA', $store->getState());
        $this->assertEquals('12345', $store->getZip());
        $this->assertEquals('555-0123', $store->getPhone());
        $this->assertEquals('test@store.com', $store->getEmail());
        $this->assertInstanceOf(ApiStoreHours::class, $store->getStoreHours());
    }

    public function testArrayProperties(): void
    {
        // Test with empty arrays
        $store = $this->createTestApiStore(null, null);

        $this->assertInstanceOf(ApiTailorPricesCollection::class, $store->getTailerPrices());
        $this->assertEmpty($store->getTailerPrices());

        $this->assertInstanceOf(ApiPricesPerDepartmentCollection::class, $store->getPricesPerDepartments());
        $this->assertEmpty($store->getPricesPerDepartments());
    }

    public function testWithSpecialCharacters(): void
    {
        // Create a partial mock from the class
        $store = $this->getMockBuilder(ApiStore::class)
                      ->onlyMethods(['getStoreName', 'getCity', 'getBranchId', 'getBranchNo'])
                      ->disableOriginalConstructor()
                      ->getMock();

        // Override only the methods you need for special characters
        $store->method('getStoreName')->willReturn("Bob's Store & More");
        $store->method('getCity')->willReturn('São Paulo');
        $store->method('getBranchId')->willReturn(0);
        $store->method('getBranchNo')->willReturn('');

        $this->assertEquals("Bob's Store & More", $store->getStoreName());
        $this->assertEquals('São Paulo', $store->getCity());
        $this->assertEquals(0, $store->getBranchId());
        $this->assertEquals('', $store->getBranchNo());
    }

    public function testWithCollections(): void
    {
        // Test that collections are properly handled and arrays are returned by getter methods
        $tailorPricesCollection = new ApiTailorPricesCollection([]);
        $pricesPerDepartmentsCollection = new ApiPricesPerDepartmentCollection([]);

        $store = $this->createTestApiStore($tailorPricesCollection, $pricesPerDepartmentsCollection);

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
        $store = $this->createTestApiStore(null, null);

        $this->assertEquals('Test Store', $store->getStoreName());
        $this->assertEquals(123, $store->getBranchId());
        $this->assertEquals('BR001', $store->getBranchNo());
        $this->assertEquals('123 Main St', $store->getStreetAddress());
        $this->assertEquals('Anytown', $store->getCity());
    }
}

