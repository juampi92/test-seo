<?php

namespace Juampi92\TestSEO\Tests;

use Juampi92\TestSEO\Support\ArrayPluck;
use PHPUnit\Framework\TestCase;

class ArrayPluckTest extends TestCase
{
    /**
     * @dataProvider pluckDataProvider
     */
    public function test_should_pluck_array(array $expected, array $items, string $key, string $value): void
    {
        $this->assertEqualsCanonicalizing(
            $expected,
            (new ArrayPluck($items))(key: $key, value: $value),
        );
    }

    public function pluckDataProvider(): array
    {
        return [
            'It plucks the values' => [
                'expected' => ['foo' => 'bar', 'foo2' => 'bar2'],
                'items' => [['name' => 'foo', 'content' => 'bar'], ['name' => 'foo2', 'content' => 'bar2']],
                'key' => 'name',
                'value' => 'content',
            ],
            'It groups results into an array when the keys are the same' => [
                'expected' => ['foo' => ['bar', 'bar2'], 'foo2' => 'bar3'],
                'items' => [
                    ['property' => 'foo', 'content' => 'bar'],
                    ['property' => 'foo', 'content' => 'bar2'],
                    ['property' => 'foo2', 'content' => 'bar3'],
                ],
                'key' => 'property',
                'value' => 'content',
            ],
        ];
    }
}
