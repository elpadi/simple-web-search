<?php
namespace SimpleSearch\Sqlite;

use Functional as F;

abstract class Results extends \SimpleSearch\Results {

	protected $db;
	protected $count = 0;
	protected $matches = [];
	protected $records = [];
	protected $tables = [];

	public function init(string $storagePath) {
		$this->db = new \SQLite3($storagePath);
		$this->db->enableExceptions(TRUE);
	}

	protected function fetchMatches(string $searchQuery) {
		foreach ($this->tables as $table) {
			$this->matches[$table] = [];
			$result = $this->db->query("SELECT rowid,snippet($table) FROM $table WHERE content MATCH ('$searchQuery*')");
			while ($row = $result->fetchArray()) {
				$this->matches[$table][] = $row;
				$this->count++;
			}
		}
	}

	protected function fetchResultsData() {
		$ids = F\unique(call_user_func_array('array_merge', F\map($this->matches, function($matches) {
			return F\pluck($matches, 'rowid');
		})));
		$rows = $this->db->query(sprintf("SELECT rowid,url,title.content AS title,description.content AS description FROM url JOIN title ON docid=rowid JOIN description USING (docid) WHERE rowid IN (%s)", implode(',', $ids)));
		while ($row = $rows->fetchArray(\SQLITE3_ASSOC)) $this->records[$row['rowid']] = $row;
	}

	protected function generateResults() {
		$this->fetchResultsData();
		foreach ($this->matches as $table => $matches) {
			foreach ($matches as $match) {
				$data = $this->records[$match[0]];
				$data['field'] = $table;
				$data['snippet'] = $match[1];
				unset($data['rowid']);
				$this->results[] = $data;
			}
		}
	}

}
