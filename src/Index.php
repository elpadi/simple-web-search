<?php
namespace SimpleSearch;

use Psr\Http\Message\RequestInterface;

class Index {

	protected $indexer;
	protected $crawler;

	public static function create() {
		return new static();
	}

	public function __construct() {
	}

	public function setIndexer(Indexer $indexer) {
		$this->indexer = $indexer;
		return $this;
	}

	public function setCrawler(Crawler $crawler) {
		$this->crawler = $crawler;
		return $this;
	}

	protected function rebuild() {
		$this->indexer->init();
		$this->indexer->deleteAll();
		$this->crawler->setIndexer($this->indexer);
		$this->crawler->start();
	}

	public function handleRequest(RequestInterface $request) {
		switch ($request->getMethod()) {
		case 'POST':
			$this->rebuild();
			break;
		case 'PUT':
			break;
		case 'DELETE':
			break;
		default:
			throw new \BadMethodCallException(sprintf('Request method %s is not valid.', $request->getMethod()));
		}
	}

}
