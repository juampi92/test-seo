<?php

namespace Juampi92\TestSEO\Tests;

use Juampi92\TestSEO\Parser\HTMLParser;
use PHPUnit\Framework\TestCase;

class HTMLParserTest extends TestCase
{
    public function test_it_parses_html_into_null_instance(): void
    {
        // Arrange
        $page = <<<'EMPTY_HTML'
<!DOCTYPE html>
<html>
    <head></head>
    <body></body>
</html>
EMPTY_HTML;

        // Act
        $parser = new HTMLParser($page);

        // Assert
        $this->assertNull($parser->grabTitle());
        $this->assertNull($parser->grabTitle());
        $this->assertNull($parser->grabDescription());
        $this->assertNull($parser->grabRobots());
        $this->assertNull($parser->grabCanonical());
        $this->assertNull($parser->grabPrev());
        $this->assertNull($parser->grabNext());
        $this->assertNull($parser->grabOpenGraph('site_name'));
        $this->assertNull($parser->grabTwitter('site_name'));
        $this->assertEmpty($parser->grabRelAlternateHrefLang());
        $this->assertEmpty($parser->grabImages());
        $this->assertEmpty($parser->grabH1s());
        $this->assertEmpty($parser->grabH2s());
        $this->assertNull($parser->grabCharset());
    }

    public function test_it_parses_html_into_instance(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test.html');

        // Act
        $parser = new HTMLParser($page);

        // Assert
        $this->assertEquals('This is my test title.', $parser->grabTitle());
        $this->assertEquals('This is the description of the test page.', $parser->grabDescription());
        $this->assertEquals('index, follow', $parser->grabRobots());
        $this->assertEquals('https://testpage.com/example.html', $parser->grabCanonical());
        $this->assertEquals('https://testpage.com/example.html?page=1', $parser->grabPrev());
        $this->assertEquals('https://testpage.com/example.html?page=3', $parser->grabNext());
        $this->assertEquals('OGFooBar', $parser->grabOpenGraph('site_name'));
        $this->assertEquals('TwitterFooBar', $parser->grabTwitter('card'));
        $this->assertEquals([
            ['hreflang' => 'es', 'href' => 'https://testpage.com/es/example.html'],
            ['hreflang' => 'pt', 'href' => 'https://testpage.com/pt/example.html'],
        ], $parser->grabRelAlternateHrefLang());
        $this->assertEquals([
            ['src' => 'test-image.jpg', 'alt' => 'My alt text', 'title' => null],
        ], $parser->grabImages());
        $this->assertEquals(['Header example'], $parser->grabH1s());
        $this->assertEquals(['Header 2 example', 'Header 2 example 2'], $parser->grabH2s());
        $this->assertEquals('utf-8', $parser->grabCharset());
//        $this->assertEquals('', $parser->grabAmpHtmlLink());
    }
}
