<?php
class RSSFeed extends RSSElement {
	protected $allowed = array('title', 'link', 'description', 'language', 'copyright', 'managingEditor', 'webMaster', 'pubDate', 'lastBuildDate', 'category', 'generator', 'docs', 'cloud', 'ttl', 'image', 'rating', 'textInput', 'skipHours', 'skipDays', 'item');
	protected $required = array('title', 'link', 'description');
	protected $properties = array('item' => array());
}

class RSSItem extends RSSElement {
	protected $allowed = array('title', 'link', 'description', 'author', 'category', 'comments', 'enclosure', 'guid', 'pubDate', 'source');
	protected $required = array('title', 'link', 'description');
}

class RSSElement {
	protected $allowed = array();
	protected $required = array();
	protected $properties = array();
	
	public function __construct($properties = array()) {
		foreach($properties AS $key => $value) {
			$this->{$key} = $value;
		}
	}
	
	public function __set($key, $value) {
		if(in_array($key, $this->allowed)) {
			$property = &$this->properties[$key];
			if(is_array($property)) {
				array_push($property, $value);
			} else {
				$this->properties[$key] = $value;
			}
		}
	}
		
	public function render(&$base, $dom) {
		foreach($this->allowed AS $key) {
			if(isset($this->properties[$key])) {
				$value = $this->properties[$key];
				
				if(is_array($value)) {
					foreach($value AS $val) {
						if($val instanceOf RSSElement) {
							$element = $dom->createElement($key);
							$val->render($element, $dom);
							$base->appendChild($element);
						} else {
							$base->appendChild($dom->createElement($key, $val));
						}
					}
				} else {
					$base->appendChild($dom->createElement($key, $value));
				}
			}
		}
		return $base;
	}
	
	public function toString() {
		$dom = new DOMDocument();
		$rss = $dom->createElement('rss');
		$rss->setAttribute('version', '2.0');
		
		$channel = $dom->createElement('channel');
		
		$this->render($channel, $dom);
		
		$rss->appendChild($channel);
		$dom->appendChild($rss);
		return $dom->saveXML();
	}
}