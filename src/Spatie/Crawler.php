<?php
namespace SimpleSearch\Spatie;

use Spatie\Crawler\Crawler as SpatieCrawler;
use Spatie\Crawler\CrawlInternalUrls;

class Crawler extends \SimpleSearch\Crawler {

	public function init(array $clientConfig=[]) {
		$this->spatie = SpatieCrawler::create($clientConfig);
		$this->spatie->setCrawlProfile(new CrawlInternalUrls($this->url));
		$this->spatie->setCrawlObserver($this);
	}

	public function start(string $url='') {
		if (empty($url)) $this->index = 1;
		$this->spatie->startCrawling($url ? $url : $this->url);
	}

}
