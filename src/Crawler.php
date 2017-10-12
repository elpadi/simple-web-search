<?php
namespace SimpleSearch;

use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

abstract class Crawler implements CrawlObserver {

	protected $url;
	protected $indexer;
	protected $index = 0;

	public function __construct(string $url) {
		$this->url = $url;
	}

	public function setIndexer(Indexer $indexer) {
		$this->indexer = $indexer;
		return $this;
	}

	abstract public function start(string $url='');

	public function willCrawl(Url $url) {
	}

	public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null) {
		$record = $this->indexer->createRecord((string)$url, (string)($response->getBody()));
		if ($this->index) $this->index++;
		$this->indexer->set($record, $this->index);
	}

	public function finishedCrawling() {
		$this->indexer->finalize();
	}

}
