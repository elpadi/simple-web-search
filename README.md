# simple-web-search

## About
Pluggable web search component using crawler + database full text search. Inspired by [Sphider](http://www.sphider.eu/index.php).

## Usage
1. Create your .env settings file.
2. Create your website classes, follow `Sites\RootBasics` as en example.
3. Make a request to `index.php` to generate the full text index.
4. Make a request to `query.php` to fetch the search results.

## HTTP Auth
Add the following block to `index.php`:

```php
use GuzzleHttp\RequestOptions;
$clientConfig = [];
if (getenv('CLIENT_USERNAME') && getenv('CLIENT_PASSWORD')) {
	$clientConfig[RequestOptions::AUTH] = [getenv('CLIENT_USERNAME'), getenv('CLIENT_PASSWORD')];
}
```

Pass the `$clientConfig` variable to the Spatie crawler constructor.
