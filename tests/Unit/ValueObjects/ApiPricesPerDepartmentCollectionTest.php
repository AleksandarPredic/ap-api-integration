<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

// Define WordPress constant to avoid "Direct script access denied" error
if (!defined('ABSPATH')) {
    define('ABSPATH', '/fake/wordpress/path/');
}

use ApApi\ValueObjects\ApiPricesPerDepartment;
use ApApi\ValueObjects\ApiPricesPerDepartmentCollection;
use PHPUnit\Framework\TestCase;

class ApiPricesPerDepartmentCollectionTest extends TestCase
{
    /**
     * Helper method to create test prices per department array
     *
     * @param int $count Number of ApiPricesPerDepartment objects to create
     * @return ApiPricesPerDepartment[]
     */
    private function createTestPricesPerDepartmentArray(int $count): array
    {
        $departments = [];
        for ($i = 1; $i <= $count; $i++) {
            $departments[] = new ApiPricesPerDepartment("Department {$i}", []);
        }
        return $departments;
    }

    public function testConstructorWithEmptyArray(): void {
        // Test empty collection
        $emptyCollection = new ApiPricesPerDepartmentCollection([]);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertEmpty($emptyCollection->getAll());
        $this->assertEquals(0, $emptyCollection->count());
    }

    public function testConstructorWithValidItems(): void {
        // Test filled collection
        $departmentsArray = $this->createTestPricesPerDepartmentArray(3);
        $collection = new ApiPricesPerDepartmentCollection($departmentsArray);

        $this->assertEquals(3, $collection->count());
        $this->assertEquals($departmentsArray, $collection->getAll());
        $this->assertFalse($collection->isEmpty());
    }

    public function testConstructorWithInvalidItems(): void {
        // Test only ApiPricesPerDepartment objects are allowed
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ApiPricesPerDepartment');

        $validDepartment = $this->createTestPricesPerDepartmentArray(1)[0];
        new ApiPricesPerDepartmentCollection([
            1,
            2,
            $validDepartment
        ]);
    }

    public function testAddingValidItems(): void
    {
        $testDepartments = $this->createTestPricesPerDepartmentArray(2);
        $department1 = $testDepartments[0];
        $department2 = $testDepartments[1];

        $collection = new ApiPricesPerDepartmentCollection([$department1]);

        $this->assertContains($department1, $collection->getAll());
        $this->assertEquals(1, $collection->count());

        $collection->add($department2);

        $this->assertContains($department2, $collection->getAll());
        $this->assertEquals(2, $collection->count());
    }

    public function testIteratorMethods(): void
    {
        $departmentsArray = $this->createTestPricesPerDepartmentArray(3);
        $collection = new ApiPricesPerDepartmentCollection($departmentsArray);

        // Test initial state
        $this->assertTrue($collection->valid());
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($departmentsArray[0], $collection->current());

        // Test next() and key progression
        $collection->next();
        $this->assertEquals(1, $collection->key());
        $this->assertEquals($departmentsArray[1], $collection->current());
        $this->assertTrue($collection->valid());

        // Move to last item
        $collection->next();
        $this->assertEquals(2, $collection->key());
        $this->assertEquals($departmentsArray[2], $collection->current());
        $this->assertTrue($collection->valid());

        // Move past end
        $collection->next();
        $this->assertEquals(3, $collection->key());
        $this->assertNull($collection->current());
        $this->assertFalse($collection->valid());

        // Test rewind()
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($departmentsArray[0], $collection->current());
        $this->assertTrue($collection->valid());
    }

    public function testRewindFunctionality(): void
    {
        $departmentsArray = $this->createTestPricesPerDepartmentArray(2);
        $collection = new ApiPricesPerDepartmentCollection($departmentsArray);

        // Move to end of collection
        $collection->next(); // position 1
        $collection->next(); // position 2 (past end)
        $this->assertFalse($collection->valid());

        // Test rewind brings us back to start
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($departmentsArray[0], $collection->current());
        $this->assertTrue($collection->valid());

        // Test multiple rewinds work correctly
        $collection->next();
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($departmentsArray[0], $collection->current());
    }

    public function testEmptyCollectionIterator(): void
    {
        $emptyCollection = new ApiPricesPerDepartmentCollection([]);

        // Test empty collection iterator behavior
        $this->assertFalse($emptyCollection->valid());
        $this->assertEquals(0, $emptyCollection->key());
        $this->assertNull($emptyCollection->current());

        // Test next() on empty collection
        $emptyCollection->next();
        $this->assertFalse($emptyCollection->valid());
        $this->assertEquals(1, $emptyCollection->key());

        // Test rewind() on empty collection
        $emptyCollection->rewind();
        $this->assertEquals(0, $emptyCollection->key());
        $this->assertFalse($emptyCollection->valid());
    }
}
