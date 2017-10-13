<?php
require(__DIR__.'/vendor/autoload.php');
define('ROOT_DIR', __DIR__);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required('SITE_NAME')->notEmpty();
$dotenv->required('INDEX_STORAGE_DIR')->notEmpty();
$dotenv->required('CRAWL_URL')->notEmpty();

$classname = sprintf('SimpleSearch\Sites\%s\Index', getenv('SITE_NAME'));
$index = new $classname();
$response = $index->handleRequest();
$response->send();

