<?php

namespace Juampi92\TestSEO\Tags;

class TagCollection
{
    public function __construct(
        private string $prefix,
        /** @var array<string, string|array<string>> */
        private array $metadata,
    ) {
    }

    /**
     * @return string|array<string>|null
     */
    public function get(string $property)
    {
        // Normalize property
        $property = $this->prefix.ltrim($property, $this->prefix);

        return $this->metadata[$property] ?? null;
    }

    public function toArray(): array
    {
        return $this->metadata;
    }
}
