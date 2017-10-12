<?php
namespace SimpleSearch;

abstract class Results {

	protected $results = [];

	public function __construct() {
	}

	abstract public function handleRequest();

	public function get() {
		return $this->results;
	}

}
