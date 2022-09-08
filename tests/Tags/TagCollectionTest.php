<?php

namespace Juampi92\TestSEO\Tests\Tags;

use Juampi92\TestSEO\Support\ArrayPluck;
use Juampi92\TestSEO\Tags\TagCollection;
use PHPUnit\Framework\TestCase;

class TagCollectionTest extends TestCase
{
    public function test_it_should_get_correctly(): void
    {
        // Arrange
        $metadata = (new ArrayPluck([
            ['property' => 'og:title', 'content' => 'My title'],
        ]))(key: 'property', value: 'content');

        // Act
        $og = new TagCollection('og:', $metadata);

        // Assert
        $this->assertEquals('My title', $og->get('title'));
        $this->assertEquals('My title', $og->get('og:title'));
        $this->assertNull($og->get('og:titles'));
    }

    public function test_it_should_get_array_values(): void
    {
        // Arrange
        $metadata = (new ArrayPluck([
            ['property' => 'og:url', 'content' => 'https://image.url/'],
            ['property' => 'og:image', 'content' => 'https://image.url/here.jpg'],
            ['property' => 'og:image', 'content' => 'https://image.url/here-2.jpg'],
        ]))(key: 'property', value: 'content');

        // Act
        $og = new TagCollection('og:', $metadata);

        // Assert
        $this->assertEquals('https://image.url/', $og->get('url'));
        $this->assertEquals([
            'https://image.url/here.jpg',
            'https://image.url/here-2.jpg',
        ], $og->get('og:image'));
    }

    public function test_it_should_convert_to_array(): void
    {
        // Arrange
        $metadata = (new ArrayPluck([
            ['name' => 'twitter:url', 'content' => 'https://image.url/'],
            ['name' => 'twitter:image', 'content' => 'https://image.url/here-1.jpg'],
            ['name' => 'twitter:image', 'content' => 'https://image.url/here-2.jpg'],
        ]))(key: 'name', value: 'content');

        // Act
        $og = new TagCollection('twitter:', $metadata);

        // Assert
        $this->assertEquals([
            'twitter:url' => 'https://image.url/',
            'twitter:image' => [
                'https://image.url/here-1.jpg',
                'https://image.url/here-2.jpg',
            ],
        ], $og->toArray());
    }
}
