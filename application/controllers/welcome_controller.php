<?php
class WelcomeController extends ApplicationController {
	public $title = array('KDE Games');
	public $kde_feed;
	const DKO_TTL = 1800;
	const DKO_URL = 'http://www.kde.org/dotkdeorg.rdf';
	
	// protected function _setup() {
	// 	$this->add_before_filter('test', 'after');
	// }
	
	public function index() {
		array_push($this->title, 'Welcome');
		// $this->kde_feed = simplexml_load_file('http://www.kde.org/dotkdeorg.rdf');
		$this->kde_feed = $this->update_dko_cache();
	}
	
	protected function update_dko_cache() {
		$dko_cache = File::join(TMP_HOME, 'dko.rss');
		if(!is_file($dko_cache) || filemtime($dko_cache) < (time() - self::DKO_TTL)) {
			$feed = new SimpleXMLElement(self::DKO_URL, null, TRUE);
			$feed->asXML($dko_cache);
		}
		
		return new SimpleXMLElement(file_get_contents($dko_cache));
		// return filemtime($dko_cache) . ' ' . (time() - self::DKO_TTL);
	}
	
	// protected function run_before_filters() {
	// 	
	// }
	
	// protected function test() {
	// 	return false;
	// }
}