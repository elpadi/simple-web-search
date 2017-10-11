<?php
namespace SimpleSearch;

use Spatie\Crawler\CrawlObserver;
use Spatie\Crawler\Url;

abstract class Crawler implements CrawlObserver {

	protected $url;
	protected $indexer;
	protected $recordClassname;
	protected $index = 0;

	public function __construct(string $url, $recordClassname) {
		$this->url = $url;
		$this->recordClassname = $recordClassname;
	}

	public function setIndexer(Indexer $indexer) {
		$this->indexer = $indexer;
		return $this;
	}

	public function createRecord(string $url, string $html) {
		return call_user_func([$this->recordClassname, 'create'], $url, $html);
	}

	abstract public function start(string $url='');

	public function willCrawl(Url $url) {
	}

	public function hasBeenCrawled(Url $url, $response, Url $foundOnUrl = null) {
		$record = $this->createRecord((string)$url, (string)($response->getBody()));
		if ($this->index) $this->index++;
		$this->indexer->set($record, $this->index);
	}

	public function finishedCrawling() {
		$this->indexer->finalize();
	}

}
