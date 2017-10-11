<?php
namespace SimpleSearch;

use Masterminds\HTML5;
use Functional as F;

abstract class Record {

	protected $url;
	protected $title;
	protected $description;
	protected $pageContent;

	public static $properties = ['url','title','description','pageContent'];

	public function __construct(string $url, string $html) {
		$this->url = $url;
		$html5 = new HTML5();
		$doc = $html5->loadHtml($html);
		$this->hydrate($doc);
	}

	public static function create(string $url, string $html) {
		return new static($url, $html);
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

	abstract protected function fetchContent(\DomDocument $doc);

	protected function hydrate(\DomDocument $doc) {
		$this->fetchTitle($doc);
		$this->fetchDescription($doc);
		$this->fetchContent($doc);
	}

	public function getValues() {
		foreach (static::$properties as $key) $values[] = $this->$key;
		return $values;
	}

	public function get($key) {
		if (!in_array($key, static::$properties)) throw new \InvalidArgumentException("Invalid key $key.");
		return $this->$key;
	}

}
