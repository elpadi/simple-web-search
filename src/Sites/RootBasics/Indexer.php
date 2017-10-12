<?php
namespace SimpleSearch\Sites\RootBasics;

use Psr\Http\Message\RequestInterface;
use Functional as F;

class Indexer extends \SimpleSearch\Sqlite\Indexer {

	protected function initFieldSettings() {
		$this->primary = Record::getPrimaryFieldName();
		$this->searchable = Record::getSearchableFieldNames();
	}

	public function createRecord(string $url, string $html) {
		$record = new Record();
		$record->hydrate($url, $html);
		return $record;
	}

}
