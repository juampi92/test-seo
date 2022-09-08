<?php

namespace Juampi92\TestSEO;

use Illuminate\Support\Traits\Macroable;
use Juampi92\TestSEO\Parser\HTMLParser;
use Juampi92\TestSEO\Support\ArrayPluck;
use Juampi92\TestSEO\Support\Memo;
use Juampi92\TestSEO\Tags\AlternateHrefLangCollection;
use Juampi92\TestSEO\Tags\Robots;
use Juampi92\TestSEO\Tags\TagCollection;
use Spatie\Url\Url;

class SEOData
{
    use Macroable;
    use Memo;

    public function __construct(
        private HTMLParser $html
    ) {
    }

    public function title(): ?string
    {
        return $this->memo('title', fn () => $this->html->grabTextFrom('//html//head//title'));
    }

    public function description(): ?string
    {
        return $this->memo(
            'description',
            fn () => $this->html->grabAttributeFrom('//html//head//meta[@name="description"]', 'content')
        );
    }

    public function image(): ?Url
    {
        return $this->memo('image', function () {
            $url = $this->html->grabAttributeFrom('//html//head//meta[@name="image"]', 'content');

            return $url ? Url::fromString($url) : null;
        });
    }

    public function robots(): Robots
    {
        return $this->memo(
            'robots',
            fn () => new Robots(
                $this->html->grabAttributeFrom('//html//head//meta[@name="robots"]', 'content') ?: ''
            )
        );
    }

    public function canonical(): ?Url
    {
        return $this->memo('canonical', function () {
            $url = $this->html->grabAttributeFrom('//html//head//link[@rel="canonical"]', 'href');

            return $url ? Url::fromString($url) : null;
        });
    }

    public function prev(): ?Url
    {
        return $this->memo('prev', function () {
            $url = $this->html->grabAttributeFrom('//html//head//link[@rel="prev"]', 'href');

            return $url ? Url::fromString($url) : null;
        });
    }

    public function next(): ?Url
    {
        return $this->memo('next', function () {
            $url = $this->html->grabAttributeFrom('//html//head//link[@rel="next"]', 'href');

            return $url ? Url::fromString($url) : null;
        });
    }

    public function openGraph(): TagCollection
    {
        return $this->memo('openGraph', function () {
            $tags = $this->html->grabMultiple(
                '//html//head//meta[starts-with(@property, "og:")]',
                ['property', 'content'],
            );

            $tags = (new ArrayPluck($tags))(key: 'property', value: 'content');

            return new TagCollection(
                prefix: 'og:',
                metadata: $tags,
            );
        });
    }

    public function twitter(): TagCollection
    {
        return $this->memo('twitter', function () {
            $tags = $this->html->grabMultiple(
                '//html//head//meta[starts-with(@name, "twitter:")]',
                ['name', 'content'],
            );

            $tags = (new ArrayPluck($tags))(key: 'name', value: 'content');

            return new TagCollection(
                prefix: 'twitter:',
                metadata: $tags,
            );
        });
    }

    public function alternateHrefLang(): AlternateHrefLangCollection
    {
        return $this->memo(
            'alternateHrefLang',
            fn () => new AlternateHrefLangCollection(
                $this->html->grabMultiple('//html//head//link[@rel="alternate"]', ['hreflang', 'href'])
            )
        );
    }

    /**
     * @return array<array{src: string, alt: string, title: string}>
     */
    public function images(): array
    {
        return $this->memo('images', fn () => $this->html->grabMultiple('//html//body//img', ['src', 'alt', 'title']));
    }

    /**
     * @return array<string>
     */
    public function h1s(): array
    {
        return $this->memo('h1s', fn () => $this->html->grabMultiple('//html//body//h1'));
    }

    /**
     * @return array<string>
     */
    public function h2s(): array
    {
        return $this->memo('h2s', fn () => $this->html->grabMultiple('//html//body//h2'));
    }

    public function charset(): ?string
    {
        return $this->memo('charset', fn () => $this->html->grabAttributeFrom('//html//head//meta[@charset]', 'charset'));
    }
}
