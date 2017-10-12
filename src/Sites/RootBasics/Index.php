<?php
namespace SimpleSearch\Sites\RootBasics;

use SimpleSearch\Spatie\Crawler;

class Index extends \SimpleSearch\Index {

	public function __construct() {
		parent::__construct();
		$this->indexer = new Indexer(sprintf('%s/%s/index.db', ROOT_DIR, getenv('INDEX_STORAGE_DIR')));
		$this->crawler = new Crawler(getenv('CRAWL_URL'));
		$this->crawler->init();
	}

}
