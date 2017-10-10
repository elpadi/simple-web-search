<?php
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7\Request;
use SimpleSearch\Index as SearchIndex;
use SimpleSearch\Records\RootBasics as Record;
use SimpleSearch\Indexers\Sqlite as SqliteIndexer;
use SimpleSearch\Crawlers\Spatie as SpatieCrawler;

require(__DIR__.'/vendor/autoload.php');

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required('CRAWL_URL')->notEmpty();
$dotenv->required('INDEX_STORAGE_DIR')->notEmpty();

$clientConfig = [];
if (getenv('CLIENT_USERNAME') && getenv('CLIENT_PASSWORD')) {
	$clientConfig[RequestOptions::AUTH] = [getenv('CLIENT_USERNAME'), getenv('CLIENT_PASSWORD')];
}

$request = new Request(
	$_SERVER['REQUEST_METHOD'],
	$_SERVER['REQUEST_URI'],
	[], // headers
	'' // body
);


$crawler = new SpatieCrawler(getenv('CRAWL_URL'), Record::class);
$crawler->init($clientConfig);
$response = SearchIndex::create()
	->setIndexer(new SqliteIndexer(sprintf('%s/%s/index.db', __DIR__, getenv('INDEX_STORAGE_DIR'))))
	->setCrawler($crawler)
	->handleRequest($request);

