<?php

// see <https://martinfowler.com/articles/collection-pipeline/>

namespace Wdir;

use Traversable;

/** @template T */
class Collection
{
    /** @var iterable<T> */
    private iterable $array;

    /**
     * @param iterable<T> $array
     * @return self<T>
     */
    public static function of(iterable $array)
    {
        return new self($array);
    }

    /** @param iterable<T> $array */
    private function __construct(iterable $array)
    {
        $this->array = $array;
    }

    /** @return iterable<T> */
    public function iterable(): iterable
    {
        return $this->array;
    }

    /** @return array<T> */
    public function array(): array
    {
        if (is_array($this->array)) {
            return $this->array;
        }
        assert($this->array instanceof Traversable);
        return iterator_to_array($this->array);
    }

    /**
     * @template S
     * @param callable(T):S $fun
     * @return self<S>
     */
    public function map(callable $fun): self
    {
        return new self((function () use ($fun) {
            foreach ($this->array as $value) {
                yield $fun($value);
            }
        })());
    }

    /**
     * @param callable(T):bool $fun
     * @return self<T>
     */
    public function filter(callable $fun): self
    {
        return new self((function () use ($fun) {
            foreach ($this->array as $value) {
                if ($fun($value)) {
                    yield $value;
                }
            }
        })());
    }

    /**
     * @template S
     * @param S $accu
     * @param callable(S,T):S $fun
     * @return S
     */
    public function reduce($accu, callable $fun)
    {
        foreach ($this->array as $value) {
            $accu = $fun($accu, $value);
        }
        return $accu;
    }

    /** @return self<T> */
    public function take(int $count): self
    {
        return new self((function () use ($count) {
            foreach ($this->array as $value) {
                if (--$count < 0) {
                    break;
                }
                yield $value;
            }
        })());
    }

    /** @return self<T> */
    public function drop(int $count): self
    {
        return new self((function () use ($count) {
            foreach ($this->array as $value) {
                if (--$count >= 0) {
                    continue;
                }
                yield $value;
            }
        })());
    }

    /**
     * @param callable(T,T):int $comparator
     * @return self<T>
     */
    public function sort(callable $comparator): self
    {
        $array = $this->array();
        usort($array, $comparator);
        return new self($array);
    }

    /**
     * @template S
     * @param callable(T):S $fun
     * @return self<non-empty-list<T>>
     */
    public function group(callable $fun): self
    {
        $res = [];
        foreach ($this->array as $value) {
            $key = $fun($value);
            if (!isset($res[$key])) {
                $res[$key] = [];
            }
            $res[$key][] = $value;
        }
        return new self($res);
    }
}
