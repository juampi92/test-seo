<?php

namespace Juampi92\TestSEO;

use Juampi92\TestSEO\Parser\HTMLParser;
use Juampi92\TestSEO\Tags\AlternateHrefLangCollection;
use Juampi92\TestSEO\Tags\Robots;
use Juampi92\TestSEO\Tags\TagCollection;
use Juampi92\TestSEO\Tags\Url;

class SEOData
{
    public function __construct(
        private HTMLParser $html
    ) {
    }

    public function title(): ?string
    {
        return $this->html->grabTextFrom('//html//head//title');
    }

    public function description(): ?string
    {
        return $this->html->grabAttributeFrom('//html//head//meta[@name="description"]', 'content');
    }

    public function robots(): Robots
    {
        return new Robots(
            $this->html->grabAttributeFrom('//html//head//meta[@name="robots"]', 'content') ?: ''
        );
    }

    public function canonical(): ?Url
    {
        $url = $this->html->grabAttributeFrom('//html//head//link[@rel="canonical"]', 'href');

        return $url ? new Url($url) : null;
    }

    public function prev(): ?Url
    {
        $url = $this->html->grabAttributeFrom('//html//head//link[@rel="prev"]', 'href');

        return $url ? new Url($url) : null;
    }

    public function next(): ?Url
    {
        $url = $this->html->grabAttributeFrom('//html//head//link[@rel="next"]', 'href');

        return $url ? new Url($url) : null;
    }

    public function openGraph(): ?TagCollection
    {
        $tags = $this->html->grabMultiple(
            '//html//head//meta[starts-with(@name, "og:")]',
            ['name', 'content'],
        );

        if (empty($tags)) {
            return null;
        }

        return new TagCollection('og:', $tags);
    }

    public function twitter(): ?TagCollection
    {
        $tags = $this->html->grabMultiple(
            '//html//head//meta[starts-with(@name, "twitter:")]',
            ['name', 'content'],
        );

        if (empty($tags)) {
            return null;
        }

        return new TagCollection('twitter:', $tags);
    }

    public function alternateHrefLang(): AlternateHrefLangCollection
    {
        return new AlternateHrefLangCollection(
            $this->html->grabMultiple('//html//head//link[@rel="alternate"]', ['hreflang', 'href'])
        );
    }

    /**
     * @return array<array{src: string, alt: string, title: string}>
     */
    public function images(): array
    {
        return $this->html->grabMultiple('//html//body//img', ['src', 'alt', 'title']);
    }

    /**
     * @return array<string>
     */
    public function h1s(): array
    {
        return $this->html->grabMultiple('//html//body//h1');
    }

    /**
     * @return array<string>
     */
    public function h2s(): array
    {
        return $this->html->grabMultiple('//html//body//h2');
    }

    public function charset(): ?string
    {
        return $this->html->grabAttributeFrom('//html//head//meta[@charset]', 'charset');
    }
}
