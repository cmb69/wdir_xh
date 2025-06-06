<?php

namespace Wdir;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testMap(): void
    {
        $squares = Collection::of([1, 2, 3])->map(fn ($num) => $num ** 2);
        $this->assertEquals([1, 4, 9], $squares->array());
    }

    public function testFilter(): void
    {
        $odds = Collection::of([1, 2, 3])->filter(fn ($num) => $num % 2 !== 0);
        $this->assertEquals([1, 3], $odds->list()->array());
    }

    public function testReduce(): void
    {
        $sum = Collection::of([1, 2, 3])->reduce(0, fn ($accu, $num) => $accu + $num);
        $this->assertSame(6, $sum);
    }

    public function testTakesTwo(): void
    {
        $actual = Collection::of([1, 2, 3, 4, 5])->take(2);
        $this->assertEquals([1, 2], $actual->list()->array());
    }

    public function testDropsTwo(): void
    {
        $actual = Collection::of([1, 2, 3, 4, 5])->drop(2);
        $this->assertEquals([3, 4, 5], $actual->list()->array());
    }

    public function testSorts(): void
    {
        $actual = Collection::of([3, 2, 5, 4, 1])->sort(fn ($a, $b) => $a <=> $b);
        $this->assertEquals([1, 2, 3, 4, 5], $actual->list()->array());
    }

    public function testsGroups(): void
    {
        $actual = Collection::of(["bat", "it", "a", "the"])->group(fn ($val) => strlen($val))->array();
        $this->assertEquals([3 => ["bat", "the"], 2 => ["it"], 1 => ["a"]], $actual);
    }
}
