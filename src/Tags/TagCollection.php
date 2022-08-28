<?php

namespace Juampi92\TestSEO\Tags;

class TagCollection
{
    /** @var array<string, string|array<string>> */
    private array $metadata;

    /**
     * @param  array<array{name: string, content: string}>  $metadata
     */
    public function __construct(
        private string $prefix,
        array $metadata,
    ) {
        $this->metadata = $this->pluck($metadata);
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

    /**
     * @param  array<array{name: string, content: string}>  $array
     * @return array<string, string|array<string>>
     */
    private function pluck(array $array): array
    {
        $results = [];

        foreach ($array as $item) {
            ['name' => $name, 'content' => $content] = $item;

            if (! isset($results[$name])) {
                $results[$name] = $content;

                continue;
            }

            // When there is already a value,
            // we have to use an array.
            if (! is_array($results[$name])) {
                $results[$name] = [$results[$name]];
            }

            array_push($results[$name], $content);
        }

        return $results;
    }
}
