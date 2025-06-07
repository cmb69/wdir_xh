<?php

namespace Wdir;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testMap(): void
    {
        $collection = Collection::of([1, 2, 3])->map(fn ($num) => $num ** 2);
        $this->assertEquals([1, 4, 9], iterator_to_array($collection, false));
    }

    public function testFilter(): void
    {
        $collection = Collection::of([1, 2, 3])->filter(fn ($num) => $num % 2 !== 0);
        $this->assertEquals([1, 3], iterator_to_array($collection, false));
    }

    public function testReduce(): void
    {
        $sum = Collection::of([1, 2, 3])->reduce(0, fn ($accu, $num) => $accu + $num);
        $this->assertSame(6, $sum);
    }

    public function testTakesTwo(): void
    {
        $collection = Collection::of([1, 2, 3, 4, 5])->take(2);
        $this->assertEquals([1, 2], iterator_to_array($collection, false));
    }

    public function testDropsTwo(): void
    {
        $collection = Collection::of([1, 2, 3, 4, 5])->drop(2);
        $this->assertEquals([3, 4, 5], iterator_to_array($collection, false));
    }

    public function testSorts(): void
    {
        $collection = Collection::of([3, 2, 5, 4, 1])->sort(fn ($a, $b) => $a <=> $b);
        $this->assertEquals([1, 2, 3, 4, 5], iterator_to_array($collection, false));
    }

    public function testsGroups(): void
    {
        $collection = Collection::of(["bat", "it", "a", "the"])->group(fn ($val) => strlen($val));
        $this->assertEquals([3 => ["bat", "the"], 2 => ["it"], 1 => ["a"]], iterator_to_array($collection));
    }
}
