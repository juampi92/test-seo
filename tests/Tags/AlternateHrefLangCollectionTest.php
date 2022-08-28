<?php

namespace Juampi92\TestSEO\Tests\Tags;

use Juampi92\TestSEO\Tags\AlternateHrefLangCollection;
use PHPUnit\Framework\TestCase;

class AlternateHrefLangCollectionTest extends TestCase
{
    public function test_should_work(): void
    {
        // Arrange
        $items = [
            ['hreflang' => 'en-us', 'href' => 'https://testsite.com/en/example.html'],
            ['hreflang' => 'es', 'href' => 'https://testsite.com/es/example.html'],
            ['hreflang' => 'pt', 'href' => 'https://testsite.com/pt/example.html'],
        ];

        // Act
        $collection = new AlternateHrefLangCollection($items);

        // Assert
        $this->assertCount(3, $collection->getHreflangs());
        $this->assertEquals('https://testsite.com/pt/example.html', $collection->get('pt'));
        $this->assertTrue($collection->has('en-us'));
        $this->assertFalse($collection->has('en-gb'));
    }
}
