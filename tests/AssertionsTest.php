<?php

namespace Juampi92\TestSEO\Tests;

use Juampi92\TestSEO\TestSEO;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;

class AssertionsTest extends TestCase
{
    public function test_should_pass_assertions(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-2.html');

        // Act
        $testSeo = new TestSEO($page);

        // Assert
        $testSeo
            ->assertCanonicalIs('https://testpage.com/en/product/44/reviews')
            ->assertTitleEndsWith('- My Web')
            ->assertTitleContains('Reviews for product #44')
            ->assertTitleIs('Reviews for product #44 - My Web')
            ->assertDescriptionIs('The product 44 is amazing.')
            ->assertRobotsIsNoIndexNoFollow()
            ->assertThereIsOnlyOneH1()
            ->assertAllImagesHaveAltText();

        $this->assertEquals('https://testpage.com/en/product/44/reviews?page=1', $testSeo->data->prev());
        $this->assertEquals('https://testpage.com/en/product/44/reviews?page=3', $testSeo->data->next());

        $this->assertTrue($testSeo->data->alternateHrefLang()->has('es'));
        $this->assertEquals('https://testpage.com/es/product/44/reviews?page=2', $testSeo->data->alternateHrefLang()->get('es'));
    }

    /**
     * @dataProvider breakAssertionsCase2DataProvider
     */
    public function test_should_break_on_assertions_case_2(callable $evaluation): void
    {
        $this->expectException(ExpectationFailedException::class);

        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-2.html');

        // Act
        $testSeo = new TestSEO($page);

        // Assert
        $evaluation($testSeo);
    }

    public function breakAssertionsCase2DataProvider(): array
    {
        return [
            'Empty canonical' => [fn (TestSEO $testSEO) => $testSEO->assertCanonicalIsEmpty()],
            'Wrong canonical' => [fn (TestSEO $testSEO) => $testSEO->assertCanonicalIs('https://testpage.com/en/product/44?asd=1')],
            'Empty Pagination' => [fn (TestSEO $testSEO) => $testSEO->assertPaginationIsEmpty()],
            'Empty Robots' => [fn (TestSEO $testSEO) => $testSEO->assertRobotsIsEmpty()],
            'Wrong Title' => [fn (TestSEO $testSEO) => $testSEO->assertTitleIs('This is my test title')],
            'Empty AlternateHreflang' => [fn (TestSEO $testSEO) => $testSEO->assertAlternateHrefLangIsEmpty()],
        ];
    }

    public function test_should_pass_assertions_on_empty_case_3(): void
    {
        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-3.html');

        // Act
        $testSeo = new TestSEO($page);

        // Assert
        $testSeo
            ->assertCanonicalIsEmpty()
            ->assertRobotsIsEmpty()
            ->assertPaginationIsEmpty()
            ->assertAlternateHrefLangIsEmpty();
    }

    /**
     * @dataProvider breakAssertionsCase3DataProvider
     */
    public function test_should_break_on_assertions_case_3(callable $evaluation): void
    {
        $this->expectException(ExpectationFailedException::class);

        // Arrange
        $page = file_get_contents(__DIR__.'/stubs/test-case-3.html');

        // Act
        $testSeo = new TestSEO($page);

        // Assert
        $evaluation($testSeo);
    }

    public function breakAssertionsCase3DataProvider(): array
    {
        return [
            'More than one h1' => [fn (TestSEO $testSEO) => $testSEO->assertThereIsOnlyOneH1()],
            'Has images with no alt' => [fn (TestSEO $testSEO) => $testSEO->assertAllImagesHaveAltText()],
        ];
    }
}
