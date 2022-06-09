<?php

declare(strict_types=1);

namespace Sharoff45\Library\Accessor;

use Sharoff45\Library\Accessor\Exception\EntityAccessorException;
use Sharoff45\Library\Accessor\Exception\NotSupportAccessorException;
use function array_merge;
use function get_class;
use function method_exists;
use function sprintf;

abstract class AbstractAccessor implements AccessorInterface
{
    /**
     * @var object|object[]|null
     */
    protected $entity;

    /**
     * @param mixed $data
     *
     * @throws NotSupportAccessorException
     */
    public function setData($data): AccessorInterface
    {
        if (!$this->supports($data)) {
            throw new NotSupportAccessorException(
                sprintf('Entity of class %s not supports by %s', get_class($data), static::class)
            );
        }

        $this->entity = $data;

        return $this;
    }

    /**
     * @param string[] $appends
     *
     * @throws EntityAccessorException
     *
     * @return array<mixed>
     */
    public function toArray(array $appends = []): array
    {
        if (null === $this->entity) {
            throw new EntityAccessorException(sprintf('Entity not set in %s', static::class));
        }

        return array_merge($this->getData(), $this->getAppends($appends));
    }

    /**
     * @throws EntityAccessorException
     *
     * @return array<mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array<mixed>
     */
    abstract protected function getData(): array;

    /**
     * @param string[] $appends
     *
     * @return array<mixed>
     */
    protected function getAppends(array $appends = []): array
    {
        $ret = [];
        foreach ($appends as $append) {
            $method = 'append'.$this->camelize($append);

            if (!method_exists($this, $method)) {
                continue;
            }

            /** @var callable $callable */
            $callable = [$this, $method];
            $ret[] = $callable($appends);
        }

        if (count($ret) > 0) {
            return array_merge(...$ret);
        }

        return [];
    }

    private function camelize(string $str): string
    {
        return str_replace([' ', '_', '-'], '', ucwords($str, ' _-'));
    }
}
