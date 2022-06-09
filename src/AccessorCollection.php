<?php

declare(strict_types=1);

namespace Sharoff45\Library\Accessor;

use Countable;
use Sharoff45\Library\Accessor\Exception\EntityAccessorException;
use Sharoff45\Library\Accessor\Exception\NotSupportAccessorException;
use function count;

final class AccessorCollection implements AccessorInterface, Countable
{
    /**
     * @var iterable<mixed>|array<mixed>|null
     */
    private $entities;

    /**
     * @var AccessorInterface
     */
    private $accessor;

    public function __construct(AccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param iterable<mixed>|array<mixed>|null $entities
     *
     * @throws NotSupportAccessorException
     */
    public static function factory(AccessorInterface $accessor, ?iterable $entities = null): AccessorCollection
    {
        $collection = new self($accessor);

        if (null !== $entities) {
            $collection->setData($entities);
        }

        return $collection;
    }

    /**
     * @param mixed $entities
     *
     * @throws NotSupportAccessorException
     */
    public function setData($entities): AccessorInterface
    {
        if (!$this->supports($entities)) {
            throw new NotSupportAccessorException('Entities should be iterable and countable');
        }

        $this->entities = $entities;

        return $this;
    }

    /**
     * @param string[] $appends
     *
     * @throws EntityAccessorException
     * @throws NotSupportAccessorException
     *
     * @return array<mixed>
     */
    public function toArray(array $appends = []): array
    {
        if (null === $this->entities) {
            throw new EntityAccessorException(sprintf('Entity not set in %s', static::class));
        }

        $ret = [];
        foreach ($this->entities as $entity) {
            $ret[] = $this->accessor->setData($entity)->toArray($appends);
        }

        return $ret;
    }

    /**
     * @param mixed $entity
     */
    public function supports($entity): bool
    {
        return is_iterable($entity) && is_countable($entity);
    }

    /**
     * @throws EntityAccessorException
     * @throws NotSupportAccessorException
     *
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @throws NotSupportAccessorException
     */
    public function count(): int
    {
        if (!is_countable($this->entities)) {
            throw new NotSupportAccessorException('Entities should be countable');
        }

        return count($this->entities);
    }
}
