<?php
namespace SimpleSearch;

abstract class Results {

	protected $count = 0;
	protected $results = [];

	public function __construct() {
	}

	abstract public function handleRequest();

	public function get() {
		return $this->results;
	}

	protected function respond() {
		return new Response(
			200,
			[
				'Content-Type' => 'application/json',
			],
			json_encode([
				'count' => $this->count,
				'results' => $this->results,
			])
		);
	}

}
