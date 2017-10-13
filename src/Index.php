<?php
namespace SimpleSearch;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

class Index {

	protected $indexer;
	protected $crawler;

	public function __construct() {
	}

	public static function createDefaultRequest() {
		return new Request(
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			[], // headers
			'' // body
		);
	}

	protected function respond(string $msg) {
		return new Response(
			200,
			[
				'Content-Type' => 'application/json',
			],
			json_encode([
				'message' => $msg
			])
		);
	}

	protected function rebuild() {
		$this->indexer->init();
		$this->indexer->deleteAll();
		$this->crawler->setIndexer($this->indexer);
		$this->crawler->start();
		return $this->respond(sprintf('Index rebuilt. %d total records in index.', $this->indexer->count));
	}

	public function handleRequest(RequestInterface $request=NULL) {
		if (!$request) $request = static::createDefaultRequest();
		switch ($request->getMethod()) {
		case 'POST':
			return $this->rebuild();
		case 'PUT':
			break;
		case 'DELETE':
			break;
		}
		throw new \BadMethodCallException(sprintf('Request method %s is not valid.', $request->getMethod()));
	}

}
