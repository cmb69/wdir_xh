<?php

// see <https://martinfowler.com/articles/collection-pipeline/>

namespace Wdir;

use IteratorAggregate;
use Traversable;

/**
 * @template V
 * @implements IteratorAggregate<int|string,V>
 */
final class Collection implements IteratorAggregate
{
    /** @var iterable<V> */
    private iterable $array;

    /**
     * @param iterable<V> $array
     * @return self<V>
     */
    public static function of(iterable $array)
    {
        return new self($array);
    }

    /** @param iterable<V> $array */
    private function __construct(iterable $array)
    {
        $this->array = $array;
    }

    /** @return Traversable<V> */
    public function getIterator(): Traversable
    {
        foreach ($this->array as $key => $val) {
            yield $key => $val;
        }
    }

    /**
     * @template V1
     * @param callable(V):V1 $fun
     * @return self<V1>
     */
    public function map(callable $fun): self
    {
        return new self((function () use ($fun) {
            foreach ($this->array as $key => $val) {
                yield $key => $fun($val);
            }
        })());
    }

    /**
     * @param callable(V):bool $fun
     * @return self<V>
     */
    public function filter(callable $fun): self
    {
        return new self((function () use ($fun) {
            foreach ($this->array as $key => $val) {
                if ($fun($val)) {
                    yield $key => $val;
                }
            }
        })());
    }

    /**
     * @template T
     * @param T $accu
     * @param callable(T,V):T $fun
     * @return T
     */
    public function reduce($accu, callable $fun)
    {
        foreach ($this->array as $value) {
            $accu = $fun($accu, $value);
        }
        return $accu;
    }

    /** @return self<V> */
    public function take(int $count): self
    {
        return new self((function () use ($count) {
            foreach ($this->array as $key => $val) {
                if (--$count < 0) {
                    break;
                }
                yield $key => $val;
            }
        })());
    }

    /** @return self<V> */
    public function drop(int $count): self
    {
        return new self((function () use ($count) {
            foreach ($this->array as $key => $val) {
                if (--$count >= 0) {
                    continue;
                }
                yield $key => $val;
            }
        })());
    }

    /**
     * @param callable(V,V):int $comparator
     * @return self<V>
     */
    public function sort(callable $comparator): self
    {
        $array = $this->array();
        uasort($array, $comparator);
        return new self($array);
    }

    /**
     * @param callable(V):(int|string) $fun
     * @return self<non-empty-list<V>>
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

    /** @return array<V> */
    private function array(): array
    {
        if (is_array($this->array)) {
            return $this->array;
        }
        assert($this->array instanceof Traversable);
        return iterator_to_array($this->array);
    }
}
