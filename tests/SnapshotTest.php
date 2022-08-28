<?php

namespace Juampi92\TestSEO\Tests;

use Juampi92\TestSEO\TestSEO;
use PHPUnit\Framework\TestCase;

class SnapshotTest extends TestCase
{
    public function test_should_create_the_snapshot(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-2.html');

        // Act
        $testSeo = new TestSEO($page);
        $snapshot = json_decode(json_encode($testSeo), true);

        // Assert
        $this->assertEquals([
            'title' => 'Reviews for product #44 - My Web',
            'canonical' => 'https://testpage.com/en/product/{id}/reviews',
            'description' => 'The product 44 is amazing.',
            'twitter' => [
                'twitter:card' => 'TwitterFooBar #44',
                'twitter:image' => 'https://testpage.com/images/products/{id}.jpg',
            ],
            'opengraph' => [
                'og:site_name' => 'OGFooBar',
                'og:image' => 'https://testpage.com/images/products/{id}.jpg',
            ],
            'robots' => 'nofollow, noindex',
            'pagination' => [
                'prev' => 'https://testpage.com/en/product/{id}/reviews?page={id}',
                'next' => 'https://testpage.com/en/product/{id}/reviews?page={id}',
            ],
            'relAltHreflang' => [
                ['hreflang' => 'es', 'href' => 'https://testpage.com/es/product/{id}/reviews?page={id}'],
                ['hreflang' => 'pt', 'href' => 'https://testpage.com/pt/product/{id}/reviews?page={id}'],
            ],
            'h1' => ['Product #44'],
        ], $snapshot);
    }

    public function test_should_create_the_snapshot_on_empty_data(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-3.html');

        // Act
        $testSeo = new TestSEO($page);
        $snapshot = json_decode(json_encode($testSeo), true);

        // Assert
        $this->assertEquals([
            'title' => 'Update product #44 - My Web',
            'canonical' => null,
            'description' => 'The product 44 is amazing.',
            'twitter' => null,
            'opengraph' => null,
            'robots' => '',
            'pagination' => [
                'prev' => null,
                'next' => null,
            ],
            'relAltHreflang' => [],
            'h1' => ['Update Product #44', 'Second h1. This must be bad.'],
        ], $snapshot);
    }

    public function test_should_create_the_tag_collection_snapshot_on_array_values(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-4.html');

        // Act
        $testSeo = new TestSEO($page);
        $snapshot = json_decode(json_encode($testSeo), true);

        // Assert
        $this->assertEquals([
            'og:image' => [
                'https://testpage.com/images/products/{id}-front.jpg',
                'https://testpage.com/images/products/{id}-back.jpg',
            ],
        ], $snapshot['opengraph']);
    }
}
