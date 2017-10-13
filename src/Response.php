<?php
namespace SimpleSearch;

class Response extends \GuzzleHttp\Psr7\Response {

	public function send() {
		header(sprintf('HTTP/1.1 %d %s', $this->getStatusCode(), $this->getReasonPhrase()));
		foreach ($this->getHeaders() as $name => $values) foreach ($values as $value) header(sprintf('%s: %s', $name, $value));
		echo (string)$this->getBody();
	}

}
