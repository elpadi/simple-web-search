<?php
namespace SimpleSearch\Sites\RootBasics;

class Results extends \SimpleSearch\Sqlite\Results {

	public function __construct() {
		$this->tables = Record::getSearchableFieldNames();
		$this->init(sprintf('%s/%s/index.db', ROOT_DIR, getenv('INDEX_STORAGE_DIR')));
	}

	public function handleRequest() {
		$this->fetchMatches($_GET['q']);
		$this->generateResults();
	}

}
