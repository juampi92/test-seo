<?php

namespace Juampi92\TestSEO\Parser;

use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

class HTMLParser
{
    private Crawler $crawler;

    public function __construct(string $html)
    {
        $this->crawler = new Crawler($html);
    }

    public function grabTitle(): ?string
    {
        return $this->grabTextFrom('//html//head//title');
    }

    public function grabDescription(): ?string
    {
        return $this->grabAttributeFrom('//html//head//meta[@name="description"]', 'content');
    }

    public function grabRobots(): ?string
    {
        return $this->grabAttributeFrom('//html//head//meta[@name="robots"]', 'content');
    }

    public function grabCanonical(): ?string
    {
        return $this->grabAttributeFrom('//html//head//link[@rel="canonical"]', 'href');
    }

    public function grabPrev(): ?string
    {
        return $this->grabAttributeFrom('//html//head//link[@rel="prev"]', 'href');
    }

    public function grabNext(): ?string
    {
        return $this->grabAttributeFrom('//html//head//link[@rel="next"]', 'href');
    }

    public function grabOpenGraph(string $type): ?string
    {
        return $this->grabAttributeFrom(
            sprintf('//html//head//meta[@name="og:%s"]', $type),
            'content'
        );
    }

    public function grabTwitter(string $type): ?string
    {
        return $this->grabAttributeFrom(
            sprintf('//html//head//meta[@name="twitter:%s"]', $type),
            'content'
        );
    }

    /**
     * @return array<array{hreflang: string, href: string}>
     */
    public function grabRelAlternateHrefLang(): array
    {
        return $this->grabMultiple('//html//head//link[@rel="alternate"]', ['hreflang', 'href']);
    }

    /**
     * @return array<array{src: string, alt: string, title: string}>
     */
    public function grabImages(): array
    {
        return $this->grabMultiple('//html//body//img', ['src', 'alt', 'title']);
    }

    /**
     * @return array<string>
     */
    public function grabH1s(): array
    {
        return $this->grabMultiple('//html//body//h1');
    }

    /**
     * @return array<string>
     */
    public function grabH2s(): array
    {
        return $this->grabMultiple('//html//body//h2');
    }

    public function grabCharset(): ?string
    {
        return $this->grabAttributeFrom('//html//head//meta[@charset]', 'charset');
    }

    /*
     * Crawler helpers.
     */

    private function grabTextFrom(string $xpath): ?string
    {
        return $this->crawler->filterXPath($xpath)->text('') ?: null;
    }

    /**
     * @param  string  $xpath
     * @param string|array<string> $attributes
     * @return string|array<string, string|null>|null
     */
    private function grabAttributeFrom(string $xpath, $attributes)
    {
        $nodes = $this->crawler->filterXPath($xpath);

        if ($nodes->count() === 0) {
            return null;
        }

        return $this->getArgumentsFromNode($nodes->getNode(0), $attributes);
    }

    /**
     * @param  string  $xpath
     * @param  string|array<string>|null  $attribute
     * @return array
     */
    private function grabMultiple(string $xpath, $attribute = null)
    {
        $result = [];
        $nodes = $this->crawler->filterXPath($xpath);

        foreach ($nodes as $node) {
            $result[] = $attribute !== null ? $this->getArgumentsFromNode($node, $attribute) : $node->textContent;
        }

        return $result;
    }

    /**
     * @param  DOMNode  $node
     * @param  string|array<string>  $attributes
     * @return string|array
     */
    private function getArgumentsFromNode(DOMNode $node, $attributes)
    {
        if (is_string($attributes)) {
            return $node->getAttribute($attributes);
        }

        $result = [];

        foreach ($attributes as $attribute) {
            $result[$attribute] = $node->getAttribute($attribute) ?? null;
        }

        return $result;
    }
}
