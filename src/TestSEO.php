<?php

namespace Juampi92\TestSEO;

use JsonSerializable;
use Juampi92\TestSEO\Parser\HTMLParser;
use Juampi92\TestSEO\SnapshotFormatters\SimpleSerializer;
use Juampi92\TestSEO\SnapshotFormatters\SnapshotSerializer;
use PHPUnit\Framework\Assert;

class TestSEO implements JsonSerializable
{
    public SEOData $data;

    private SnapshotSerializer $snapshotSerializer;

    public function __construct(string $content, ?SnapshotSerializer $snapshotSerializer = null)
    {
        $html = new HTMLParser($content);
        $this->data = new SEOData($html);
        $this->snapshotSerializer = $snapshotSerializer ?? new SimpleSerializer();
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

    public function assertAlternateHrefLangIsEmpty(): self
    {
        Assert::assertEmpty($this->data->alternateHrefLang()->getHreflangs());

        return $this;
    }

    public function assertTitleIs(string $expected): self
    {
        Assert::assertEquals($expected, $this->data->title());

        return $this;
    }

    public function assertTitleContains(string $expected): self
    {
        Assert::assertStringContainsString($expected, $this->data->title());

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

    public function assertThereIsOnlyOneH1(): self
    {
        Assert::assertCount(1, $this->data->h1s(), 'It was expected to have exactly one header 1.');

        return $this;
    }

    public function assertAllImagesHaveAltText(): self
    {
        $imagesWithoutAlt = array_filter(
            $this->data->images(),
            fn (array $image): bool => empty($image['alt'])
        );

        Assert::assertEmpty($imagesWithoutAlt, 'Some images were missing alt text.');

        return $this;
    }

    /*
     * To Snapshot
     */

    public function jsonSerialize(): mixed
    {
        return $this->snapshotSerializer->toArray($this->data);
    }
}
