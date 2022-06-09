<?php

declare(strict_types=1);

namespace Sharoff45\Library\Accessor;

use Sharoff45\Library\Accessor\Exception\EntityAccessorException;
use Sharoff45\Library\Accessor\Exception\NotSupportAccessorException;
use JsonSerializable;

interface AccessorInterface extends JsonSerializable
{
    /**
     * @param mixed $data
     *
     * @throws NotSupportAccessorException
     *
     * @return self
     */
    public function setData($data): AccessorInterface;

    /**
     * @param mixed $entity
     */
    public function supports($entity): bool;

    /**
     * @param string[] $appends
     *
     * @throws EntityAccessorException
     * @throws NotSupportAccessorException
     *
     * @return array<mixed>
     */
    public function toArray(array $appends = []): array;
}
