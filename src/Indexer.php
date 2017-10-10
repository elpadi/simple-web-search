<?php
namespace SimpleSearch;

interface Indexer {

	public function set(Record $record);
	public function delete(string $url);
	public function deleteAll();
	public function finalize();

}
