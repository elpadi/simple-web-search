<?php
namespace SimpleSearch\Records;

use SimpleSearch\Record;

class RootBasics extends Record {

	protected function fetchContent(\DomDocument $doc) {
		$page = $doc->getElementById('page');
		if ($page) {
			foreach ($page->childNodes as $node)
				if (in_array($node->nodeName, ['HEADER','FOOTER']))
					$page->removeChild($node);
			$this->content = $doc->saveHtml($page);
		}
		else {
			$content = $doc->getElementById('content');
			$this->content = $doc->saveHtml($content);
		}
		$this->content = preg_replace('/\s+/', ' ', trim(strip_tags($this->content)));
	}

}
