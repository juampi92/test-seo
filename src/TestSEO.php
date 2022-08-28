<?php

namespace Juampi92\TestSEO;

use Juampi92\TestSEO\Parser\HTMLParser;
use PHPUnit\Framework\Assert;

class TestSEO
{
    public SEOData $data;

    public function __construct(string $content)
    {
        $html = new HTMLParser($content);
        $this->data = new SEOData($html);
    }

    /*
     * Assertions:
     */

    public function assertCanonicalIs(string $expected): self
    {
        Assert::assertEquals($expected, $this->data->canonical());

        return $this;
    }

    public function assertCanonicalIsEmpty(): self
    {
        Assert::assertNull($this->data->canonical());

        return $this;
    }

    public function assertRobotsIsEmpty(): self
    {
        Assert::assertTrue($this->data->robots()->isEmpty());

        return $this;
    }

    public function assertRobotsIsNoIndexNoFollow(): self
    {
        $robots = $this->data->robots();

        Assert::assertTrue($robots->noindex(), 'Robots should be noindex and nofollow, but found: '.(string) $robots);
        Assert::assertTrue($robots->nofollow(), 'Robots should be noindex and nofollow, but found: '.(string) $robots);

        return $this;
    }

    public function assertPaginationIsEmpty(): self
    {
        Assert::assertNull($this->data->prev(), 'Pagination previous should be empty');
        Assert::assertNull($this->data->next(), 'Pagination next should be empty');

        return $this;
    }

    public function assertAlternateHrefLangsIsEmpty(): self
    {
        Assert::assertTrue($this->data->alternateHrefLang());

        return $this;
    }

    public function assertTitleIs(string $expected): self
    {
        Assert::assertEquals($expected, $this->data->title());

        return $this;
    }

    public function assertTitleEndsWith(string $expected): self
    {
        Assert::assertStringEndsWith($expected, $this->data->title());

        return $this;
    }

    public function assertDescriptionIs(string $expected): self
    {
        Assert::assertEquals($expected, $this->data->description());

        return $this;
    }

    /*
     * To Snapshot
     */

    public function toArray(): array
    {
        return [];
    }
}
