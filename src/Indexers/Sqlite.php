<?php
namespace SimpleSearch\Indexers;

use Psr\Http\Message\RequestInterface;
use Functional as F;
use SimpleSearch\Record;
use SimpleSearch\Indexer;

class Sqlite implements Indexer {

	protected $db;
	protected $storagePath;

	protected $primary = 'url';
	protected $searchable = ['title','description','pageContent'];
	protected $rowId = 0;

	public function __construct($storagePath) {
		if (!(is_writable($storagePath) || (!file_exists($storagePath) && is_writable(dirname($storagePath))))) {
			throw new \InvalidArgumentException("Path $storagePath is not writable.");
		}
		$this->storagePath = $storagePath;
	}

	protected function fetchRow(string $sql, callable $callback) {
		$result = $this->db->query($sql);
		$row = $result->fetchArray();
		return $row ? call_user_func($callback, $row) : NULL;
	}

	protected function fetchRowId(string $value) {
		return $this->fetchRow("SELECT rowid FROM $this->primary WHERE $this->primary='$value'", function($row) {
			return $row ? $row[0] : 0;
		});
	}

	protected function fetchNextRowId() {
		return $this->fetchRow("SELECT MAX(rowid) FROM $this->primary WHERE $this->primary='$value'", function($row) {
			return $row ? $row[0] + 1 : 0;
		});
	}

	protected function repeatQuery($query, $tables) {
		foreach ($tables as $table) $this->db->exec(sprintf($query, $table));
	}

	public function init() {
		$this->db = new \SQLite3($this->storagePath);
		$this->db->enableExceptions(TRUE);
	}

	public function set(Record $record, int $index) {
		$primaryVal = $record->get($this->primary);
		$rowId = $index ? $index : $this->fetchRowId($primaryVal);
		if (!$rowId) {
			$rowId = $this->fetchNextRowId();
		}
		$this->db->exec(sprintf('INSERT OR REPLACE INTO %s (rowid, %1$s) VALUES (%d, "%s")', $this->primary, $rowId, $primaryVal));
		foreach ($this->searchable as $table)
			$this->db->exec(sprintf("INSERT INTO %s (rowid, content) VALUES (%d, '%s')", $table, $rowId, \SQLite3::escapeString($record->get($table))));
	}

	public function delete(string $url) {
		if ($rowId = $this->fetchRowId($url)) {
			$this->repeatQuery("DELETE FROM %s WHERE rowid=$rowId", $this->searchable);
		}
	}

	public function deleteAll() {
		$this->repeatQuery('DROP TABLE IF EXISTS %s', array_merge([$this->primary], $this->searchable));
		$this->db->exec(sprintf('CREATE TABLE %s (rowid INTEGER PRIMARY KEY ASC, %1$s VARCHAR(255) UNIQUE)', $this->primary));
		$this->repeatQuery('CREATE VIRTUAL TABLE %s USING fts4()', $this->searchable);
	}

	public function finalize() {
		$this->db->close();
	}

}
