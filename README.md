# Test SEO

[![Latest Version on Packagist](https://img.shields.io/packagist/v/juampi92/test-seo.svg?style=flat-square)](https://packagist.org/packages/juampi92/test-seo)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/juampi92/test-seo/run-tests?label=tests)](https://github.com/juampi92/test-seo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/juampi92/test-seo/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/juampi92/test-seo/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/juampi92/test-seo.svg?style=flat-square)](https://packagist.org/packages/juampi92/test-seo)

An easy-to-use package for testing SEO. The package allows you to extract SEO tags from a given HTML and verify that the SEO structure is correct.

- [Installation](#instalation)
- [Usage](#usage)
  - [PHPUnit](#phpunit)
  - [Laravel](#laravel)
  - [Pest](#pest)
- [API](#seo-data)
  - [SEO Data](#seo-data)
  - [Assertions](#sssertions)
- [Snapshot testing](#snapshots)

----
## Installation

You can install the package via composer:

```bash
composer require juampi92/test-seo --dev
```

## Usage

```php
// Create TestSEO instance using the response:
$seo = new TestSEO($htmlResponse);

// Perform assertions:
$seo->assertTitleEndsWith(' - My Website');

// Assert the data yourself:
$this->assertEquals(
    'My title - My Website',
    $seo->data->title()
);
```

Look at the following examples using **PHPUnit**, **Laravel**, and **Pest**.

### PHPUnit

```php
public function testLandingPageSEO()
{
    // Arrange
    // ...
    
    // Act
    $response = $client->get('/')->send();

    // Assert
    $this->assertEquals(200, $response->getStatusCode());
    $html = json_decode($response->getBody(true), true);

    $seo = new TestSEO($html);
    
    // Assert
    $seo
        ->assertTitleEndsWith(' - My Website')
        ->assertCanonicalIs('https://www.mywebsite.com/');
}
```

### Laravel

```php
public function test_landing_page_SEO()
{
    // Arrange
    // ...
    
    // Act
    $response = $this->get('/');

    // Assert
    $response->assertStatus(200);

    $seo = new TestSEO($response->getContent());
    
    $seo
        ->assertTitleEndsWith(' - My Website')
        ->assertCanonicalIs('https://www.mywebsite.com/');
}
```

### Pest

```php
test('landing page SEO tags', function () {
    // Arrange
    // ...
    
    // Act
    $response = get('/')->assertStatus(200);
    
    $seo = new TestSEO($response->getContent());
    
    // Assert
    expect($seo->data)
        ->title()->toEndWith(' - My Website')
        ->description()->toBe('This is my description')
        ->canonical()->not()->toBeNull()
        ->robots()->index()->toBeTrue()
        ->robots()->nofollow()->toBeTrue();
});
```

## SEO Data

You can access the SEO Data yourself by accessing the public property `TestSEO->data`.
Here are the available methods:

| Method                | Returns                                                                                                                     | Description                                                 |
|-----------------------|-----------------------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------|
| `title()`             | `?string`                                                                                                                   | `<title>{this}</title>`                                     |
| `description()`       | `?string`                                                                                                                   | `<meta name="description" content="{this}">`                |
| `image()`             | `?Url` [🔍](https://github.com/spatie/url)                                                                                                                   | `<meta name="image" content="{this}">`                      |
| `robots()`            | `Robots` [🔍](https://github.com/juampi92/test-seo/blob/main/src/Tags/Robots.php)                                           | `<meta name="robots" content="{this}">`                     |
| `canonical()`         | `?Url` [🔍](https://github.com/spatie/url)                                                                                  | `<link rel="canonical" href="{this}">`                      |
| `prev()`              | `?Url` [🔍](https://github.com/spatie/url)                                                                                  | `<link rel="prev" href="{this}">`                           |
| `next()`              | `?Url` [🔍](https://github.com/spatie/url)                                                                                  | `<link rel="next" href="{this}">`                           |
| `openGraph()`         | `TagCollection` [🔍](https://github.com/juampi92/test-seo/blob/main/src/Tags/TagCollection.php)                             | `<meta property="og:{key}" content="{value}">`                  |
| `twitter()`           | `TagCollection` [🔍](https://github.com/juampi92/test-seo/blob/main/src/Tags/TagCollection.php)                             | `<meta name="twitter:{key}" content="{value}">`             |
| `alternateHrefLang()` | `AlternateHrefLangCollection` [🔍](https://github.com/juampi92/test-seo/blob/main/src/Tags/AlternateHrefLangCollection.php) | `<link name="alternate" hreflang="{hreflang}" href={href}>` |
| `images()`            | `array<array{src: string, alt: string, title: string}>`                                                                     | All images in the page. `<img src="...">`                   |
| `h1s()`               | `array<string>`                                                                                                             | All H1 in the page. `<h1>{this}</h1>`                       |
| `h2s()`               | `array<string>`                                                                                                             | All H2 in the page. `<h2>{this}</h2>`                       |
| `charset()`           | `?string`                                                                                                                   | `<meta charset="utf-8">`                                    |

The SEOData class is **Macroable**, so feel free to extend it yourself. 

## Assertions

| Method                                  | Notes                                                                                                     |
|-----------------------------------------|-----------------------------------------------------------------------------------------------------------|
| `assertCanonicalIs(string $expected)`   |                                                                                                           |
| `assertCanonicalIsEmpty()`              |                                                                                                           |
| `assertRobotsIsEmpty()`                 |                                                                                                           |
| `assertRobotsIsNoIndexNoFollow()`       | Checks that the robots are `noindex, nofollo` or `none`                                                   |
| `assertPaginationIsEmpty()`             | `prev` and `next` are both missing.                                                                       |
| `assertAlternateHrefLangIsEmpty()`      |                                                                                                           |
| `assertTitleIs(string $expected)`       |                                                                                                           |
| `assertTitleContains(string $expected)` |                                                                                                           |
| `assertTitleEndsWith(string $expected)` |                                                                                                           |
| `assertDescriptionIs(string $expected)` |                                                                                                           |
| `assertThereIsOnlyOneH1()`              | Make sure there is only one H1 in the entire website.                                                     |
| `assertAllImagesHaveAltText()`          | Make sure all images have an `alt="..."`                                                                  |
| Suggest your own!                       | These assertions can help devs to follow the best SEO practices. Make a PR if you think some are missing! |

## Snapshots

When it comes to SEO, a snapshot test is a great way to ensure nothing has been changed by accident.

Here is an example:

```php
$seo = new TestSEO($response->getContent(), snapshotSerializer: null);

$json = json_encode($seo);
```

By default, the SEO tags are serialized using the `SimpleSerializer`.
Make your own serializer by implementing the `SnapshotSerializer` interface:

```php
$seo = new TestSEO($response->getContent(), new MyCustomSerializer());

$json = json_encode($seo);
```

### Pest Example

```php
use function Spatie\Snapshots\{assertMatchesSnapshot, assertMatchesJsonSnapshot};
use Juampi92\TestSEO\TestSEO;

test('landing page SEO', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    $seo = new TestSEO($response->getContent());

    assertMatchesJsonSnapshot(json_encode($seo));
});
```

**Note:** this example requires `spatie/pest-plugin-snapshots`.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Juan Pablo Barreto](https://github.com/juampi92)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
