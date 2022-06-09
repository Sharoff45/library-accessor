<?php

declare(strict_types=1);

namespace Sharoff45\Library\Accessor\Tests\Fixtures;

use Sharoff45\Library\Accessor\AbstractAccessor;
use function is_a;

/**
 * @property TestEntity $entity
 */
class TestAccessor extends AbstractAccessor
{
    public const APPEND_PROPERTY = 'property';

    /**
     * @param mixed $entity
     */
    public function supports($entity): bool
    {
        return is_a($entity, TestEntity::class, true);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        return [
            'name' => $this->entity->getName(),
            'amount' => $this->entity->getValue(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function appendProperty(): array
    {
        return [
            'property' => 'added_property',
        ];
    }
}
