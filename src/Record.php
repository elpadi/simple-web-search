<?php
namespace SimpleSearch;

use Masterminds\HTML5;
use Functional as F;

abstract class Record {

	protected $url;
	protected $title;
	protected $description;

	protected static $primary = 'url';
	protected static $searchable = ['title','description'];

	public function __construct() {
	}

	public function hydrate(string $url, string $html) {
		$this->url = $url;
		$html5 = new HTML5();
		$doc = $html5->loadHtml($html);
		$this->fetchData($doc);
	}

	protected function fetchData(\DomDocument $doc) {
		$this->fetchTitle($doc);
		$this->fetchDescription($doc);
	}

	protected function fetchTitle(\DomDocument $doc) {
		$this->title = F\first(
			F\pluck(iterator_to_array($doc->getElementsByTagName('title')), 'textContent')
		);
	}

	protected function fetchDescription(\DomDocument $doc) {
		$this->description = F\first(
			F\invoke(F\filter(iterator_to_array($doc->getElementsByTagName('meta')), function($node) {
				return $node->getAttribute('name') === 'description';
			}), 'getAttribute', ['content'])
		);
	}

	public static function getPrimaryFieldName() {
		return static::$primary;
	}

	public static function getSearchableFieldNames() {
		return static::$searchable;
	}

	public function get($key='') {
		$keys = empty($key) ? array_merge(['url'], static::$searchable) : [$key];
		foreach ($keys as $key) if (isset($this->$key)) $values[] = $this->$key;
		return count($keys) === 1 ? (isset($values) ? $values[0] : NULL) : (isset($values) ? $values : []);
	}

}
