<?php

namespace Juampi92\TestSEO\Tags;

use Stringable;

class Url implements Stringable
{
    public function __construct(
        private string $url
    ) {
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
