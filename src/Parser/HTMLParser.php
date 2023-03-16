<?php

namespace Juampi92\TestSEO\Parser;

use DOMElement;
use DOMNode;
use Symfony\Component\DomCrawler\Crawler;

class HTMLParser
{
    private Crawler $crawler;

    public function __construct(string $html)
    {
        $this->crawler = new Crawler($html);
    }

    public function grabTextFrom(string $xpath): ?string
    {
        return $this->crawler->filterXPath($xpath)->text('') ?: null;
    }

    /**
     * @param  string|array<string>  $attributes
     * @return string|array<string, string|null>|null
     */
    public function grabAttributeFrom(string $xpath, $attributes)
    {
        $nodes = $this->crawler->filterXPath($xpath);

        if ($nodes->count() === 0) {
            return null;
        }

        return $this->getArgumentsFromNode($nodes->getNode(0), $attributes);
    }

    /**
     * @param  string|array<string>|null  $attribute
     */
    public function grabMultiple(string $xpath, $attribute = null): array
    {
        $result = [];
        $nodes = $this->crawler->filterXPath($xpath);

        foreach ($nodes as $node) {
            $result[] = $attribute !== null ? $this->getArgumentsFromNode($node, $attribute) : $node->textContent;
        }

        return $result;
    }

    /**
     * @param  DOMElement|DOMNode|null  $element
     * @param  string|array<string>  $attributes
     * @return string|array<string, string|null>
     */
    private function getArgumentsFromNode($element, $attributes)
    {
        if (! $element || ! ($element instanceof DOMElement)) {
            return [];
        }

        if (is_string($attributes)) {
            return $element->getAttribute($attributes);
        }

        $result = [];

        foreach ($attributes as $attribute) {
            $result[$attribute] = $element->getAttribute($attribute) ?: null;
        }

        return $result;
    }
}
