<?php
namespace SimpleSearch\Sites\RootBasics;

use Functional as F;

class Record extends \SimpleSearch\Record {
	
	protected $pageContent;
	protected static $searchable = ['title','description','pageContent'];

	protected function fetchData(\DomDocument $doc) {
		parent::fetchData($doc);
		$this->fetchContent($doc);
	}

	protected function fetchContent(\DomDocument $doc) {
		$page = $doc->getElementById('page');
		if ($page) {
			$content = implode(' ', F\map(F\filter(iterator_to_array($page->childNodes), function($node) {
				return !in_array($node->nodeName, ['#text','header','footer']);
			}), function($node) use ($doc) {
				return $doc->saveHtml($node);
			}));
		}
		else {
			$element = $doc->getElementById('content');
			$content = $doc->saveHtml($element);
		}
		$this->pageContent = preg_replace('/\s+/', ' ', trim(strip_tags($content)));
	}

}
