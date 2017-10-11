<?php
namespace SimpleSearch\Crawlers;

use SimpleSearch\Record;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlInternalUrls;

class Spatie extends \SimpleSearch\Crawler {

	public function init(array $clientConfig) {
		$this->spatie = Crawler::create($clientConfig);
		$this->spatie->setCrawlProfile(new CrawlInternalUrls($this->url));
		$this->spatie->setCrawlObserver($this);
	}

	public function start(string $url='') {
		if (empty($url)) $this->index = 1;
		$this->spatie->startCrawling($url ? $url : $this->url);
	}

}
