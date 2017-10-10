<?php
namespace SimpleSearch\Indexers;

use Psr\Http\Message\RequestInterface;
use Functional as F;
use SimpleSearch\Record;
use SimpleSearch\Indexer;

class Sqlite implements Indexer {

	protected $db;
	protected $storagePath;

	public function __construct($storagePath) {
		if (!(is_writable($storagePath) || (!file_exists($storagePath) && is_writable(dirname($storagePath))))) {
			throw new \InvalidArgumentException("Path $storagePath is not writable.");
		}
		$this->storagePath = $storagePath;
	}

	public function init() {
		$this->db = new \SQLite3($this->storagePath);
		$this->db->enableExceptions(TRUE);
	}

	public function set(Record $record) {
		$this->delete($record->get('url'));
		$this->db->exec(sprintf('INSERT INTO pages (%s) VALUES(%s)',
			implode(',', Record::$properties),
			implode(',', F\map($record->getValues(), function($value) { return sprintf("'%s'", \SQLite3::escapeString($value)); }))
		));
	}

	public function delete(string $url) {
		$this->db->exec(sprintf("DELETE FROM pages WHERE url='%s'", $url));
	}

	public function deleteAll() {
		$this->db->exec('DROP TABLE IF EXISTS pages');
		$this->db->exec(sprintf('CREATE VIRTUAL TABLE pages USING fts4(%s)', implode(',', Record::$properties)));
	}

	public function finalize() {
		$this->db->close();
	}

}
