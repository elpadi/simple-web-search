# simple-web-search
Pluggable web search component using crawler + database full text search. Inspired by [Sphider](http://www.sphider.eu/index.php).

## HTTP Auth
use GuzzleHttp\RequestOptions;
$clientConfig = [];
if (getenv('CLIENT_USERNAME') && getenv('CLIENT_PASSWORD')) {
	$clientConfig[RequestOptions::AUTH] = [getenv('CLIENT_USERNAME'), getenv('CLIENT_PASSWORD')];
}

Pass the $clientConfig variable to the Spatie crawler constructor
