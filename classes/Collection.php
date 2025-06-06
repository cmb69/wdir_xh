<?php

// see <https://martinfowler.com/articles/collection-pipeline/>

namespace Wdir;

/** @template T */
class Collection
{
    /** @var list<T> */
    private array $array;

    /**
     * @param list<T> $array
     * @return self<T>
     */
    public static function of(array $array)
    {
        return new self($array);
    }

    /** @param list<T> $array */
    private function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @return list<T>
     */
    public function array(): array
    {
        return $this->array;
    }

    /**
     * @template S
     * @param callable(T):S $fun
     * @return self<S>
     */
    public function map(callable $fun): self
    {
        $array = [];
        foreach ($this->array as $value) {
            $array[] = $fun($value);
        }
        return new self($array);
    }

    /**
     * @param callable(T):bool $fun
     * @return self<T>
     */
    public function filter(callable $fun): self
    {
        $array = [];
        foreach ($this->array as $value) {
            if ($fun($value)) {
                $array[] = $value;
            }
        }
        return new self($array);
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
}
