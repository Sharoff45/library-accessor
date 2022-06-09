<?php

declare(strict_types=1);

namespace Sharoff45\Library\Accessor\Tests;

use Sharoff45\Library\Accessor\AccessorCollection;
use Sharoff45\Library\Accessor\Exception\EntityAccessorException;
use Sharoff45\Library\Accessor\Exception\NotSupportAccessorException;
use Sharoff45\Library\Accessor\Tests\Fixtures\TestAccessor;
use Sharoff45\Library\Accessor\Tests\Fixtures\TestEntity;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\RecursionContext\InvalidArgumentException;

/**
 * @internal
 */
class AccessorCollectionTest extends TestCase
{
    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NotSupportAccessorException
     */
    public function testSetData(): void
    {
        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor);

        $collection->setData([
            new TestEntity('name1', 1),
            new TestEntity('name2', 2),
        ]);

        self::assertEquals(2, $collection->count());
    }

    /**
     * @throws NotSupportAccessorException
     */
    public function testSetDataException(): void
    {
        $this->expectException(NotSupportAccessorException::class);
        $this->expectExceptionMessage('Entities should be iterable and countable');

        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor);

        $collection->setData(new TestEntity('name1', 1));
    }

    /**
     * @throws EntityAccessorException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NotSupportAccessorException
     */
    public function testToArray(): void
    {
        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor, [
            new TestEntity('name1', 1),
            new TestEntity('name2', 2),
        ]);
        $array = $collection->toArray();

        self::assertEquals(2, $collection->count());
        self::assertArrayHasKey('name', $array[0]);
        self::assertArrayHasKey('amount', $array[0]);
        self::assertEquals('name1', $array[0]['name']);
        self::assertEquals(1, $array[0]['amount']);
        self::assertArrayHasKey('name', $array[1]);
        self::assertArrayHasKey('amount', $array[1]);
        self::assertEquals('name2', $array[1]['name']);
        self::assertEquals(2, $array[1]['amount']);
    }

    /**
     * @throws EntityAccessorException
     * @throws NotSupportAccessorException
     */
    public function testToArrayException(): void
    {
        $this->expectException(EntityAccessorException::class);
        $this->expectExceptionMessage(sprintf('Entity not set in %s', AccessorCollection::class));

        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor);

        $collection->toArray();
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NotSupportAccessorException
     */
    public function testSupports(): void
    {
        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor);

        self::assertTrue($collection->supports([new TestEntity('name1', 1)]));
        self::assertFalse($collection->supports(new TestEntity('name1', 1)));
    }

    /**
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NotSupportAccessorException
     */
    public function testCount(): void
    {
        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor, [
            new TestEntity('name1', 1),
            new TestEntity('name2', 2),
        ]);

        self::assertEquals(2, $collection->count());
    }

    /**
     * @throws NotSupportAccessorException
     */
    public function testCountException(): void
    {
        $this->expectException(NotSupportAccessorException::class);
        $this->expectExceptionMessage('Entities should be countable');

        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor);

        $collection->count();
    }

    /**
     * @throws EntityAccessorException
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws NotSupportAccessorException
     */
    public function testAppend(): void
    {
        $accessor = new TestAccessor();
        $collection = AccessorCollection::factory($accessor, [
            new TestEntity('name1', 1),
        ]);

        $array = $collection->toArray([TestAccessor::APPEND_PROPERTY]);

        self::assertEquals(1, $collection->count());
        self::assertArrayHasKey('name', $array[0]);
        self::assertArrayHasKey('amount', $array[0]);
        self::assertArrayHasKey('property', $array[0]);
        self::assertEquals('name1', $array[0]['name']);
        self::assertEquals(1, $array[0]['amount']);
        self::assertEquals('added_property', $array[0]['property']);
    }
}
