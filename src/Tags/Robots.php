<?php

namespace Juampi92\TestSEO\Tags;

use JsonSerializable;
use Stringable;

class Robots implements Stringable, JsonSerializable
{
    public const INDEX = 'index';

    public const NO_INDEX = 'noindex';

    public const FOLLOW = 'follow';

    public const NO_FOLLOW = 'nofollow';

    public const NONE = 'none'; // Equivalent to using both the noindex and nofollow tags simultaneously.

    public const NO_IMAGE_INDEX = 'noimageindex';

    public const NO_ARCHIVE = 'noarchive';

    public const NO_CACHE = 'nocache';

    public const NO_SNIPPET = 'nosnippet';

    /** @var array<Robots::*> */
    private array $parameters;

    public function __construct(
        string $content
    ) {
        /** @var array<Robots::*> */
        $parameters = array_map(
            'trim',
            explode(',', $content)
        );

        sort($parameters);

        $this->parameters = array_filter($parameters);
    }

    public function index(): ?bool
    {
        if ($this->has(self::INDEX)) {
            return true;
        }

        if ($this->has(self::NO_INDEX)) {
            return false;
        }

        if ($this->has(self::NONE)) {
            return false;
        }

        return null;
    }

    public function noindex(): ?bool
    {
        return $this->negation(
            $this->index()
        );
    }

    public function follow(): ?bool
    {
        if ($this->has(self::FOLLOW)) {
            return true;
        }

        if ($this->has(self::NO_FOLLOW)) {
            return false;
        }

        if ($this->has(self::NONE)) {
            return false;
        }

        return null;
    }

    public function nofollow(): ?bool
    {
        return $this->negation($this->follow());
    }

    public function noarchive(): bool
    {
        return $this->has(self::NO_ARCHIVE);
    }

    public function nocache(): bool
    {
        return $this->has(self::NO_CACHE);
    }

    public function nosnippet(): bool
    {
        return $this->has(self::NO_SNIPPET);
    }

    public function noimageindex(): bool
    {
        return $this->has(self::NO_IMAGE_INDEX);
    }

    public function isEmpty(): bool
    {
        return count($this->parameters) === 0;
    }

    /*
     * Support methods.
     */

    private function has(string $parameter): bool
    {
        return in_array($parameter, $this->parameters);
    }

    /**
     * If null, return null.
     * If boolean, return the negation.
     */
    private function negation(?bool $value): ?bool
    {
        if (is_null($value)) {
            return null;
        }

        return ! $value;
    }

    public function __toString(): string
    {
        return implode(', ', $this->parameters);
    }

    public function jsonSerialize(): mixed
    {
        return (string) $this;
    }
}
