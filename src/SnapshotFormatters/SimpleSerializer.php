<?php

namespace Juampi92\TestSEO\SnapshotFormatters;

use Juampi92\TestSEO\SEOData;
use Juampi92\TestSEO\Tags\TagCollection;

class SimpleSerializer implements SnapshotSerializer
{
    public function toArray(SEOData $data): array
    {
        return [
            'title' => $data->title(),
            'description' => $data->description(),
            'robots' => (string) $data->robots(),
            'canonical' => $this->formatUrl($data->canonical()),
            'pagination' => [
                'prev' => $this->formatUrl($data->prev()),
                'next' => $this->formatUrl($data->next()),
            ],
            'relAltHreflang' => array_map(
                fn (array $item) => [
                    'hreflang' => $item['hreflang'],
                    'href' => $this->formatUrl($item['href']),
                ],
                $data->alternateHrefLang()->jsonSerialize()
            ),
            'h1' => $data->h1s(),
            'opengraph' => $this->formatTagCollection($data->openGraph()),
            'twitter' => $this->formatTagCollection($data->twitter()),
        ];
    }

    protected function formatTagCollection(TagCollection $collection): ?array
    {
        return array_map(
            function ($item) {
                if (is_array($item)) {
                    return array_map(fn (string $url) => $this->formatIfUrl($url), $item);
                }

                return $this->formatIfUrl($item);
            },
            $collection->toArray(),
        ) ?: null;
    }

    protected function formatIfUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        if (! str_starts_with($url, 'http')) {
            // Don't format strings that don't start with http
            return $url;
        }

        return $this->formatUrl($url);
    }

    protected function formatUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        return preg_replace('/\d+/', '{id}', $url);
    }
}
