<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects;

// Define WordPress constant to avoid "Direct script access denied" error
if (!defined('ABSPATH')) {
    define('ABSPATH', '/fake/wordpress/path/');
}

use ApApi\ValueObjects\ApiTailorPrice;
use ApApi\ValueObjects\ApiTailorPricesCollection;
use PHPUnit\Framework\TestCase;

class ApiTailorPricesCollectionTest extends TestCase
{
    /**
     * Helper method to create test tailor prices array
     *
     * @param int $count Number of ApiTailorPrice objects to create
     * @return ApiTailorPrice[]
     */
    private function createTestTailorPricesArray(int $count): array
    {
        $prices = [];
        for ($i = 1; $i <= $count; $i++) {
            $prices[] = new ApiTailorPrice("Price {$i}", $i * 10);
        }
        return $prices;
    }

    public function testConstructorWithEmptyArray(): void {
        // Test empty collection
        $emptyCollection = new ApiTailorPricesCollection([]);

        $this->assertTrue($emptyCollection->isEmpty());
        $this->assertEmpty($emptyCollection->getAll());
        $this->assertEquals(0, $emptyCollection->count());
    }

    public function testConstructorWithValidItems(): void {
        // Test filled collection
        $pricesArray = $this->createTestTailorPricesArray(3);
        $collection = new ApiTailorPricesCollection($pricesArray);

        $this->assertEquals(3, $collection->count());
        $this->assertEquals($pricesArray, $collection->getAll());
        $this->assertFalse($collection->isEmpty());
    }

    public function testConstructorWithInvalidItems(): void {
        // Test only ApiTailorPrice objects are allowed
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must be instances of ApiTailorPrice');

        $validPrice = $this->createTestTailorPricesArray(1)[0];
        new ApiTailorPricesCollection([
            1,
            2,
            $validPrice
        ]);
    }

    public function testAddingValidItems(): void
    {
        $testPrices = $this->createTestTailorPricesArray(2);
        $price1 = $testPrices[0];
        $price2 = $testPrices[1];

        $collection = new ApiTailorPricesCollection([$price1]);

        $this->assertContains($price1, $collection->getAll());
        $this->assertEquals(1, $collection->count());

        $collection->add($price2);

        $this->assertContains($price2, $collection->getAll());
        $this->assertEquals(2, $collection->count());
    }

    public function testIteratorMethods(): void
    {
        $pricesArray = $this->createTestTailorPricesArray(3);
        $collection = new ApiTailorPricesCollection($pricesArray);

        // Test initial state
        $this->assertTrue($collection->valid());
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($pricesArray[0], $collection->current());

        // Test next() and key progression
        $collection->next();
        $this->assertEquals(1, $collection->key());
        $this->assertEquals($pricesArray[1], $collection->current());
        $this->assertTrue($collection->valid());

        // Move to last item
        $collection->next();
        $this->assertEquals(2, $collection->key());
        $this->assertEquals($pricesArray[2], $collection->current());
        $this->assertTrue($collection->valid());

        // Move past end
        $collection->next();
        $this->assertEquals(3, $collection->key());
        $this->assertNull($collection->current());
        $this->assertFalse($collection->valid());

        // Test rewind()
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($pricesArray[0], $collection->current());
        $this->assertTrue($collection->valid());
    }

    public function testRewindFunctionality(): void
    {
        $pricesArray = $this->createTestTailorPricesArray(2);
        $collection = new ApiTailorPricesCollection($pricesArray);

        // Move to end of collection
        $collection->next(); // position 1
        $collection->next(); // position 2 (past end)
        $this->assertFalse($collection->valid());

        // Test rewind brings us back to start
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($pricesArray[0], $collection->current());
        $this->assertTrue($collection->valid());

        // Test multiple rewinds work correctly
        $collection->next();
        $collection->rewind();
        $this->assertEquals(0, $collection->key());
        $this->assertEquals($pricesArray[0], $collection->current());
    }

    public function testEmptyCollectionIterator(): void
    {
        $emptyCollection = new ApiTailorPricesCollection([]);

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
