<?php

namespace Juampi92\TestSEO\Support;

class ArrayPluck
{
    /**
     * @param  array<array<string, string>>  $items
     */
    public function __construct(private array $items)
    {
    }

    /**
     * @return array<string, string|array<string>>
     */
    public function __invoke(string $key, string $value): array
    {
        $array = $this->items;
        $results = [];

        foreach ($array as $item) {
            $itemValue = $item[$value];
            $itemKey = $item[$key];

            if (! isset($results[$itemKey])) {
                $results[$itemKey] = $itemValue;

                continue;
            }

            // When there is already a value,
            // we have to use an array.
            if (! is_array($results[$itemKey])) {
                $results[$itemKey] = [$results[$itemKey]];
            }

            array_push($results[$itemKey], $itemValue);
        }

        return $results;
    }
}
