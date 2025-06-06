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
        $this->assertEquals([1, 3], $odds->array());
    }

    public function testReduce(): void
    {
        $sum = Collection::of([1, 2, 3])->reduce(0, fn ($accu, $num) => $accu + $num);
        $this->assertSame(6, $sum);
    }

    public function testTakesTwo(): void
    {
        $actual = Collection::of([1, 2, 3, 4, 5])->take(2)->array();
        $this->assertEquals([1, 2], $actual);
    }

    public function testDropsTwo(): void
    {
        $actual = Collection::of([1, 2, 3, 4, 5])->drop(2)->array();
        $this->assertEquals([3, 4, 5], $actual);
    }

    public function testSorts(): void
    {
        $actual = Collection::of([3, 2, 5, 4, 1])->sort(fn ($a, $b) => $a <=> $b)->array();
        $this->assertEquals([1, 2, 3, 4, 5], $actual);
    }
}
