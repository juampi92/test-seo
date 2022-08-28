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
        $this->assertNull($parser->grabTextFrom('//html//head//title'));
        $this->assertEmpty($parser->grabMultiple('//html//head//meta', 'content'));
        $this->assertNull($parser->grabAttributeFrom('//html//head//link[@rel="canonical"]', 'href'));
    }

    public function test_it_can_grab_text(): void
    {
        // Arrange
        $page = <<<'HTML'
<!DOCTYPE html>
<html>
    <body>
        <p>This is the text inside a P</p>    
    </body>
</html>
HTML;

        // Act
        $parser = new HTMLParser($page);

        // Assert
        $this->assertEquals(
            'This is the text inside a P',
            $parser->grabTextFrom('//html//body//p')
        );
    }

    public function test_it_can_grab_multiple(): void
    {
        // Arrange
        $page = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <meta name="oops" foo="cool" bar="story">
        <meta name="oops" foo="cool_2" bar="story_2" nope="yup">    
    </head>
</html>
HTML;

        // Act
        $parser = new HTMLParser($page);

        // Assert
        $this->assertEquals(
            ['cool', 'cool_2'],
            $parser->grabMultiple('//html//head//meta[@name="oops"]', 'foo'),
            'It can\'t extract a single attribute'
        );
        $this->assertEquals(
            [null, 'yup'],
            $parser->grabMultiple('//html//head//meta[@name="oops"]', 'nope'),
            'It can\'t extract an single optional attribute'
        );
        $this->assertEquals(
            [
                ['foo' => 'cool', 'bar' => 'story', 'nope' => ''],
                ['foo' => 'cool_2', 'bar' => 'story_2', 'nope' => 'yup'],
            ],
            $parser->grabMultiple('//html//head//meta[@name="oops"]', ['foo', 'bar', 'nope']),
            'It can\'t extract multiple attributes'
        );
    }

    public function test_it_can_grab_attribute(): void
    {
        // Arrange
        $page = <<<'HTML'
<!DOCTYPE html>
<html>
    <head>
        <meta name="oops" foo="cool" bar="story">
        <meta name="oops" foo="cool_2" bar="story_2" nope="yup">    
    </head>
</html>
HTML;

        // Act
        $parser = new HTMLParser($page);

        // Assert
        $this->assertEquals(
            'cool',
            $parser->grabAttributeFrom('//html//head//meta[@name="oops"]', 'foo'),
            'It can\'t extract a single attribute'
        );
        $this->assertEquals(
            null,
            $parser->grabAttributeFrom('//html//head//meta[@name="oops"]', 'nope'),
            'It can\'t extract an single optional attribute'
        );
        $this->assertEquals(
            ['foo' => 'cool', 'bar' => 'story', 'nope' => ''],
            $parser->grabAttributeFrom('//html//head//meta[@name="oops"]', ['foo', 'bar', 'nope']),
            'It can\'t extract multiple attributes'
        );
    }
}
