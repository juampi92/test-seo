<?php

namespace Juampi92\TestSEO\Tags;

use JsonSerializable;

class AlternateHrefLangCollection implements JsonSerializable
{
    /** @var array<array{hreflang: string, href: string}> */
    private array $items;

    public function __construct(
        array $items
    ) {
        $this->items = $items;
    }

    public function get(string $hreflang): ?string
    {
        foreach ($this->items as $item) {
            ['hreflang' => $itemHreflang, 'href' => $href] = $item;

            if ($hreflang === $itemHreflang) {
                return $href;
            }
        }

        return null;
    }

    public function has(string $hreflang): bool
    {
        return ! is_null($this->get($hreflang));
    }

    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }

    /**
     * @return array<string>
     */
    public function getHreflangs(): array
    {
        return array_map(function (array $item) {
            return $item['hreflang'];
        }, $this->items);
    }

    public function jsonSerialize(): mixed
    {
        return $this->items;
    }
}
