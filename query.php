<?php
use Functional as F;

require(__DIR__.'/vendor/autoload.php');
define('ROOT_DIR', __DIR__);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
$dotenv->required('SITE_NAME')->notEmpty();
$dotenv->required('INDEX_STORAGE_DIR')->notEmpty();

$classname = sprintf('SimpleSearch\Sites\%s\Results', getenv('SITE_NAME'));
$results = new $classname();
/*
$param = isset($_GET[getenv('QUERY_PARAM')]) ? $_GET[getenv('QUERY_PARAM')] : '';
$classname = getenv('SEARCH_RESULTS_CLASSNAME');
 */
$results = new $classname();
$response = $results->handleRequest();
var_dump($results->get());
