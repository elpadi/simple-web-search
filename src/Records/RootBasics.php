<?php
namespace SimpleSearch\Records;

use Functional as F;
use SimpleSearch\Record;

class RootBasics extends Record {

	protected function fetchContent(\DomDocument $doc) {
		$page = $doc->getElementById('page');
		if ($page) {
			$this->content = implode(' ', F\map(F\filter(iterator_to_array($page->childNodes), function($node) {
				return !in_array($node->nodeName, ['#text','header','footer']);
			}), function($node) use ($doc) {
				return $doc->saveHtml($node);
			}));
		}
		else {
			$content = $doc->getElementById('content');
			$this->content = $doc->saveHtml($content);
		}
		$this->content = preg_replace('/\s+/', ' ', trim(strip_tags($this->content)));
	}

}
