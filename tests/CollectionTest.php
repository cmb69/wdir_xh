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
}
